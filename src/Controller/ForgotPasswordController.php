<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManager;
use App\Form\ForgotPasswordType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ForgotPasswordController extends AbstractController
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * @Route("/password/forgot", name="forgot_password", methods={"GET", "POST"})
     */
    public function getEmail(Request $request, Mailer $mailer)
    {
        $form = $this->createForm(ForgotPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $this->userRepository->findOneBy(['email' => $email]);

            if (!$user) {
                throw new NotFoundHttpException("User not found.");
            }

            $token = sha1(random_bytes(32));
            $user->setPasswordResetToken($token);
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $mailer->send($user->getEmail(), 'Reset Password', 'reset', ['url' => $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL)]);
            $this->addFlash('success', 'A password reset confirmation was sent to your email.');

            return $this->redirectToRoute('forgot_password');
        }

        return $this->render('security/forgot_password.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Reset the password.
     * 
     * @Route("/password/reset/{token}", name="reset_password", methods={"GET", "POST"})
     *
     * @param Request $request
     * @return void
     */
    public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->userRepository->findOneBy(['passwordResetToken' => $token]);

            if (!$user) {
                throw new NotFoundHttpException("User not found.");
            }

            $user->setPassword($encoder->encodePassword($user, $form->get('plainPassword')->getData()));
            $user->setPasswordResetToken(null);
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Your password was reset successfully.');

            return $this->redirectToRoute('app_logout');
        }

        return $this->render('security/reset_password.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
