<?php

namespace App\Security\EntryPoint;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Http\HttpUtils;

class LoginFormAuthenticationEntryPoint implements AuthenticationEntryPointInterface
{
    private $httpUtils;

    public function __construct(HttpUtils $httpUtils)
    {
        $this->httpUtils = $httpUtils;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $route = 'auth.login'; // TODO: get that from the config

        if (false !== strpos($request->attributes->get('_route'), 'api.')) {
            throw new UnauthorizedHttpException('', 'You must be logged in to do this!');
        }

        return $this->httpUtils->createRedirectResponse($request, $route);
    }
}
