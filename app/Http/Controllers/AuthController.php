<?php

namespace App\Http\Controllers;

use App\Application\UseCases\GenerateAuthToken;
use App\Application\UseCases\RegisterUser;
use App\Http\Requests\RegisterUserRequest;
use App\Shared\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    private RegisterUser $registerUser;
    private GenerateAuthToken $generateAuthToken;

    public function __construct(RegisterUser $registerUser, GenerateAuthToken $generateAuthToken)
    {
        $this->registerUser = $registerUser;
        $this->generateAuthToken = $generateAuthToken;
    }

    public function register(RegisterUserRequest $request): JsonResponse|ResponseFactory
    {
        $user = $this->registerUser->execute($request->email, $request->name, $request->password);
        $bearerToken = $this->generateAuthToken->execute($user);
        $data = array_merge($user->getUserArray(), ['token' => $bearerToken]);

        return Response::render(HttpResponse::HTTP_OK,
            'User registered',
            $data,
            $request->isJson()
        );

    }
}
