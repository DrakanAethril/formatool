<?php

namespace App\Security\Voter;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

use App\Config\AclPrivilegesEnum;
use App\Config\AclRessourcesEnum;
use App\Entity\Places;
use App\Entity\Trainings;

class AclVoter extends Voter
{
    private $testedRessource = '';
    private $testedPerm = '';
    
    public function __construct(
        private RequestStack $requestStack,
        private EntityManagerInterface $entityManager,
        private Security $security,
        
    ) {
       
    }

    protected function supports(string $attribute, mixed $subject): bool
    {

        $aAttributes = explode('|',$attribute);
        if(count($aAttributes) != 2 ) return false;

        $this->testedRessource = $aAttributes[0];
        $this->testedPerm = $aAttributes[1];

        return in_array($aAttributes[0], AclRessourcesEnum::names())
            && in_array($aAttributes[1], AclPrivilegesEnum::names())
            && is_object($subject);
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        if ($this->security->isGranted('ROLE_ADMIN')) { // Platform admin has all rights.
            return true;
        }
        //return true;
        $user = $token->getUser();
        $sessionAppData = $this->requestStack->getSession()->get('AclPermissions');
        $permissions = empty($sessionAppData['perms']) ? [] : $sessionAppData['perms'];
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // superadmin of the platfor is.... superadmin
        //if(in_array('ROLE_ADMIN', $user->getRoles())) return true;
        
        if($subject instanceof Trainings) {
            if(substr($this->testedRessource, 0, 8) != 'TRAINING') return false;
            if(
                in_array('PLACE_ALL|ALL'.'|'.$subject->getPlace()->getId(), $permissions) ||
                in_array('PLACE_ALL|'.$this->testedPerm.'|'.$subject->getPlace()->getId(), $permissions) ||
                in_array('PLACE_ALL_TRAININGS|ALL'.'|'.$subject->getPlace()->getId(), $permissions) ||
                in_array('PLACE_ALL_TRAININGS|'.$this->testedPerm.'|'.$subject->getPlace()->getId(), $permissions) ||
                in_array('TRAINING_ALL|ALL'.'|'.$subject->getId(), $permissions) ||
                in_array('TRAINING_ALL|'.$this->testedPerm.'|'.$subject->getId(), $permissions)
            ) {
                return true;
            }
        }
        if($subject instanceof Places) {
            if(substr($this->testedRessource, 0, 5) != 'PLACE') return false;
            if(
                in_array('PLACE_ALL|ALL'.'|'.$subject->getId(), $permissions) ||
                in_array('PLACE_ALL|'.$this->testedPerm.'|'.$subject->getId(), $permissions)
            ) {
                return true;
            }
        }
        
        return (
            in_array($this->testedRessource.'|'.$this->testedPerm.'|'.$subject->getId(), $permissions) || 
            in_array($this->testedRessource.'|ALL|'.$subject->getId(), $permissions)
        ) ;
    }
}
