<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class LoginSuccessHandler implements AuthenticationSuccessHandlerInterface
{
    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token): RedirectResponse
    {
        $roles = $token->getUser()->getRoles();

        if (in_array('ROLE_ADMIN', $roles, true)) {
            // Redirige les administrateurs vers le dashboard
            return new RedirectResponse($this->router->generate('admin'));
        }

        // Redirige les utilisateurs non administrateurs vers la page d'accueil utilisateur
        return new RedirectResponse($this->router->generate('app_account'));
    }
}