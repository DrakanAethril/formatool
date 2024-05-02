<?php

namespace App\Security\Voter;

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
        private EntityManagerInterface $entityManager
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
        $user = $token->getUser();
        $attributes = $token->getAttributes();
        $permissions = $this->requestStack->getSession()->get('AclPermissions');
        
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($subject instanceof Trainings) {

        }

        if($subject instanceof Places) {
            
        }

        return in_array($this->testedRessource.'|'.$this->testedPerm.'|'.$subject->getId(), $permissions);
    }
}
