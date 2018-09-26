<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authentication\SimplePreAuthenticatorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Keychain;
use Lcobucci\JWT\Signer\Rsa\Sha256;

class JWTTokenAuthenticator implements SimplePreAuthenticatorInterface, AuthenticationFailureHandlerInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function createToken(Request $request, $providerKey)
    {
        $jwtToken = $request->headers->get('Authorization');

        if (!$jwtToken) {
            throw new BadCredentialsException();
        }

        return new PreAuthenticatedToken(
            'anon.',
            $jwtToken,
            $providerKey
        );
    }

    public function supportsToken(TokenInterface $token, $providerKey)
    {
        return $token instanceof PreAuthenticatedToken && $token->getProviderKey() === $providerKey;
    }

    public function authenticateToken(TokenInterface $token, UserProviderInterface $userProvider, $providerKey)
    {
        $jwtToken = (new Parser())->parse($token->getCredentials());

        $signer = new Sha256();

        $keychain = new Keychain();

        if (!$jwtToken->verify($signer, $keychain->getPublicKey($this->container->getParameter('shop_api.jwt_public_key')))) {
            throw new BadCredentialsException();
        }

        $user = $userProvider->loadUserByUsername('anon.');

        return new PreAuthenticatedToken(
            $user,
            $jwtToken,
            $providerKey,
            $user->getRoles()
        );
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new HttpException(401, strtr($exception->getMessageKey(), $exception->getMessageData()));
    }
}
