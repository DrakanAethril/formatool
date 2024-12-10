<?php

namespace App\Controller;

use App\Config\UsersRolesTrainingsEnum;
use App\Config\UsersStatusTrainingsEnum;
use App\Entity\Trainings;
use App\Entity\Users;
use App\Entity\UsersTrainings;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\EmailVerifier;
use App\Security\LoginAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // get the training
            $training = $form->get('training')->getData();
            
            $user->setPassword(rand());
            $user->setRoles(["ROLE_STUDENT"]);



            $entityManager->persist($user);
            $entityManager->flush();

            $userTraining = new UsersTrainings();
            $userTraining->setUser($user);
            $userTraining->setTraining($training);
            $userTraining->setRoles([UsersRolesTrainingsEnum::STUDENT->value]);
            $userTraining->setStatus(UsersStatusTrainingsEnum::PENDING->value);

            $entityManager->persist($userTraining);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('no-reply@moncampus.net', 'Campus Beaupeyrat'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de votre email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('app_register_thank', ['training' => $training->getId()]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register/thank/{training<\d+>}', name: 'app_register_thank')]
    public function registerThankYou(Trainings $training) {
        return $this->render('registration/register-thank.html.twig', [
            'training' => $training
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(Request $request, TranslatorInterface $translator, UsersRepository $usersRepository): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $usersRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Vore email est vérifié.');

        return $this->redirectToRoute('app_login');
    }
}
