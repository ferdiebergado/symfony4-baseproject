<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class VerificationController extends AbstractController
{
    /**
     * @Route("/verify/{token}", name="app_verify")
     */
    public function verify(string $token, UserRepository $userRepository): Response
    {
        $user = $userRepository->findOneBy(['verificationToken' => $token]);

        if (! $user) {
            throw new NotFoundHttpException('User not found');
        }

        $user->setVerifiedAt(new DateTime());
        $user->setVerificationToken(null);
        $user->setIsActive(true);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $this->addFlash('success', 'You have successfully verified your account.');

        return $this->redirectToRoute('app_login');
    }
}
