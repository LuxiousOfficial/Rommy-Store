<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\AuthStoreRequest;
use App\Http\Requests\LoginStoreRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\AuthRepositoryInterface;

class AuthController extends Controller
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository) {
        $this->authRepository = $authRepository;
    }

    public function register(AuthStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $user = $this->authRepository->register($request);
            return ResponseHelper::jsonResponse(true, 'Registration successfully', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function login(LoginStoreRequest $request)
    {
        $request = $request->validated();
        try {
            $user = $this->authRepository->login($request);
            return ResponseHelper::jsonResponse(true, 'Login successfully', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function getProfile()
    {
        try {
            $user = $this->authRepository->getProfile();
            return ResponseHelper::jsonResponse(true, 'Profile successfully retrieved', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }

    public function logout()
    {
        try {
            $user = $this->authRepository->logout();
            return ResponseHelper::jsonResponse(true, 'Logout successfully', new UserResource($user), 200);
        } catch (\Exception $e) {
            return ResponseHelper::jsonResponse(false, $e->getMessage(), null, 500);
        }
    }
}
