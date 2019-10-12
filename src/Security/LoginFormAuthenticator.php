<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

/**
 * LoginFormAuthenticator
 */
class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UrlGeneratorInterface */
    private $urlGenerator;

    /** @var CsrfTokenManagerInterface */
    private $csrfTokenManager;

    /** @var UserPasswordEncoderInterface */
    private $passwordEncoder;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @inheritDoc
     */
    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'app_login'
            && $request->isMethod('POST');
    }

    /**
     * @inheritDoc
     */
    public function getCredentials(Request $request): array
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    /**
     * @inheritDoc
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (! $this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $credentials['email']]);

        if (! $user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        if ($user->getVerifiedAt() === null) {
            // fail authentication when user has not verified his email
            throw new CustomUserMessageAuthenticationException('Please check your email and verify your account.');
        }

        if (! $user->getIsActive()) {
            // fail authentication when user account is deactivated
            throw new CustomUserMessageAuthenticationException('Your account is disabled.');
        }

        return $user;
    }

    /**
     * @inheritDoc
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * @inheritDoc
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey): Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);

        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse($this->urlGenerator->generate('home'));
    }

    /**
     * @inheritDoc
     */
    protected function getLoginUrl(): string
    {
        return $this->urlGenerator->generate('app_login');
    }
}
