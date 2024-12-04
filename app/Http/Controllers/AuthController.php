<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthService; // Use AuthService, not AuthRepository
use App\Http\Requests\Auth\RequestLogin; // Import the custom request class
use App\Http\Requests\Auth\RequestRegister;
use App\Http\Helpers\ResponseHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    private $authService; // Correct variable name

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(RequestLogin $request)
    {
        try {
            return $this->authService->LoginServices($request);
        } catch (\Exception $e) {
            // Handle the exception and return a response
          return ResponseHelpers::sendError('Something went wrong',[],500);
        }
    }

    public function register(RequestRegister $request)
    {
        try{
        return $this->authService->RegisterServices($request);
        } catch (\Exception $e) {
            Log::error('Logout failed: '.$e->getMessage(), ['exception' => $e]);

          return ResponseHelpers::sendError('Something went wrong',[],500);
        }
    }

    public function profile(Request $request)
    {
        try{
        return $this->authService->ProfileServices($request);
        } catch (\Exception $e) {

            // Handle the exception and return a response
          return ResponseHelpers::sendError('Something went wrong',[],500);
        }
    }

    public function logout(Request $request)
    {
        try {
        return $this->authService->LogOutServices($request);
        } catch (\Exception $e) {
            // Handle the exception and return a response
          return ResponseHelpers::sendError('Something went wrong',[],500);
        }
    }
}
