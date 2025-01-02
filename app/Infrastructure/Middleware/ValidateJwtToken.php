<?php

namespace App\Infrastructure\Middleware;

use App\Domain\User\Interfaces\AuthenticationServiceInterface;
use App\Shared\Response;
use Closure;

class ValidateJwtToken
{
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function handle($request, Closure $next)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return Response::render(401, 'Unauthorized', $request->isJson());
        }

        $jwtPayload = $this->authenticationService->decodeJwtToken($token);

        if (is_null($jwtPayload) || !$this->authenticationService->isValidJwtToken($token)) {
            return Response::render(401, 'Invalid Token', $request->isJson());
        }

        $request->attributes->add(['jwt' => $jwtPayload]);

        return $next($request);
    }
}
