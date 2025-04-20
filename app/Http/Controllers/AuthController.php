<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserSignInRequest;
use App\Http\Requests\UserSignUpRequest;
use App\Http\Responses\Response;
use App\Services\UserService;
use Illuminate\Http\Request;
use Throwable;

class AuthController extends Controller
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    public function register(UserSignUpRequest $request)
    {
        $data = [];
        try {
            $data = $this->userService->register($request->validated());
            return Response::Success($data['user'], $data['message']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
    public function Login(UserSignInRequest $request)
    {
        $data = [];
        try {
            $data = $this->userService->Login($request);
            return Response::Success($data['user'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
    public function Logout()
    {
        $data = [];
        try {
            $data = $this->userService->logout();
            return Response::Success($data['user'], $data['message'], $data['code']);
        } catch (Throwable $th) {
            $message = $th->getMessage();
            return Response::Error($data, $message);
        }
    }
}
