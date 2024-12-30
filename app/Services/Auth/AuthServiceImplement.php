<?php

namespace App\Services\Auth;

use LaravelEasyRepository\Service;
use Illuminate\Support\Facades\Hash;
use App\Http\Helpers\ResponseHelpers;
use App\Http\Helpers\ResponseMapper;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class AuthServiceImplement extends Service implements AuthService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(UserRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function LoginServices($request)
    {
      try {
  
        // cari user lewat email
        $user = $this->mainRepository->findByEmail($request->email);
    
        if (is_null($user)) {
            return ResponseMapper::NotFound('No user found');
        }
    
        // Check password
        if (!Hash::check($request->password, $user->password)) {
            return ResponseMapper::Unauthorized('The provided credentials are incorrect.');
        }
    
        // Generate token using Passport
        $token = $user->createToken('authToken')->accessToken;
    
        return ResponseMapper::Success([
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
        ], 'Login Successful');
      } catch (\Exception $e) {
      return ResponseMapper::InternalServerError('Something went wrong during login',[$e->getMessage()]);
    }
    }
    
    public function RegisterServices($request)
    {
      DB::beginTransaction();
      try{
            $hashedPassword = Hash::make($request->password);

            $user = $this->mainRepository->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => $hashedPassword,
            ]);

      DB::commit();

            $token = $user->createToken('authToken')->accessToken;

            return ResponseMapper::Success([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ],
            ], 'Registration Successful');
              } catch (\Exception $e) {
      DB::rollBack();
      Log::error('Logout failed: '.$e->getMessage(), ['exception' => $e]);

              return ResponseMapper::InternalServerError('Something went wrong during Register',$e->getMessage());
            }
    }

      // Log out the user by revoking their token
      public function LogOutServices($request)
      {
          try {
              // Get the current authenticated user
              $user = Auth::user();

              // Revoke all of the user's tokens
              $user->tokens->each(function ($token) {
                  $token->delete();
              });

              return ResponseMapper::Success('Logout successful');
          } catch (\Exception $e) {
              return ResponseMapper::InternalServerError('Something went wrong while logging out', [$e->getMessage()]);
          }
      }

      // Get the profile details of the authenticated user
      public function ProfileServices($request)
      {
          try {
              // Get the authenticated user
              $user = Auth::user();

              if (!$user) {
                  return ResponseMapper::NotFound('User not found');
              }

              return ResponseMapper::Success('Profile fetched successfully', [
                  'user' => [
                      'id' => $user->id,
                      'name' => $user->name,
                      'email' => $user->email,
                  ]
              ]);
          } catch (\Exception $e) {
              return ResponseMapper::InternalServerError('Something went wrong while fetching profile', [$e->getMessage()]);
          }
      }

}

