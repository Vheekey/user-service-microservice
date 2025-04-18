<?php

namespace App\Infrastructure\Middleware;

use App\Domain\User\Interfaces\AuthenticationServiceInterface;
use App\Shared\Response;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ValidateJwtToken
{
    private AuthenticationServiceInterface $authenticationService;

    public function __construct(AuthenticationServiceInterface $authenticationService)
    {
        $this->authenticationService = $authenticationService;
    }

    public function handle($request, Closure $next): JsonResponse|Response|ResponseFactory
    {
        $token = $request->bearerToken();

        if (!$token) {
            return Response::render(SymfonyResponse::HTTP_UNAUTHORIZED, 'Unauthorized', [], $request->isJson());
        }

        $jwtPayload = $this->authenticationService->decodeJwtToken($token);

        if (is_null($jwtPayload) || !$this->authenticationService->isValidJwtToken($token)) {
            return Response::render(SymfonyResponse::HTTP_UNAUTHORIZED, 'Invalid Token', [], $request->isJson());
        }

        $request->attributes->add(['jwt' => $jwtPayload]);

        return $next($request);
    }
}
