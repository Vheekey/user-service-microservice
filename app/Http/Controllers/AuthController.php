<?php

namespace App\Http\Controllers;

use App\Application\UseCases\RegisterUser;
use App\Http\Requests\RegisterUserRequest;
use App\Shared\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class AuthController extends Controller
{
    private RegisterUser $registerUser;

    public function __construct(RegisterUser $registerUser)
    {
        $this->registerUser = $registerUser;
    }

    public function register(RegisterUserRequest $request): JsonResponse|ResponseFactory
    {
        $user = $this->registerUser->execute($request->email, $request->name, $request->password);

        return Response::render(HttpResponse::HTTP_OK,
            'User registered',
            $user,
            $request->isJson()
        );

    }
}
