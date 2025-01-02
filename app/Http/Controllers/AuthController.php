<?php

namespace App\Http\Controllers;

use App\Application\UseCases\AuthenticateUserCredentials;
use App\Application\UseCases\GenerateAuthToken;
use App\Application\UseCases\RegisterUser;
use App\Domain\User\Entities\User;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Shared\Response;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    private RegisterUser $registerUser;
    private GenerateAuthToken $generateAuthToken;
    private AuthenticateUserCredentials $authenticateUserCredentials;

    public function __construct(
        RegisterUser                $registerUser,
        GenerateAuthToken           $generateAuthToken,
        AuthenticateUserCredentials $authenticateUserCredentials
    )
    {
        $this->registerUser = $registerUser;
        $this->generateAuthToken = $generateAuthToken;
        $this->authenticateUserCredentials = $authenticateUserCredentials;
    }

    public function register(RegisterUserRequest $request): JsonResponse|ResponseFactory
    {
        $user = $this->registerUser->execute($request->email, $request->name, $request->password);
        $bearerToken = $this->generateAuthToken->execute($user);

        return Response::render(HttpResponse::HTTP_OK,
            'User registered',
            $this->mergeTokenData($user, $bearerToken),
            $request->isJson()
        );
    }

    /**
     * @throws Exception
     */
    public function login(LoginRequest $request): JsonResponse|ResponseFactory
    {
        $user = $this->authenticateUserCredentials->execute($request->email, $request->password);

        if (!$user) {
            return Response::render(HttpResponse::HTTP_UNAUTHORIZED,
                'Invalid credentials',
                [],
                $request->isJson());
        }

        $bearerToken = $this->generateAuthToken->generateTokenViaEmail($request->email);

        return Response::render(HttpResponse::HTTP_OK,
            'Login Successful',
            $this->mergeTokenData($user, $bearerToken),
            $request->isJson()
        );
    }

    private function mergeTokenData(User $user, string $bearerToken): array
    {
        return array_merge($user->getUserArray(), ['token' => $bearerToken]);
    }
}
