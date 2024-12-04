<?php

namespace App\Services\Auth;

use LaravelEasyRepository\BaseService;

interface AuthService extends BaseService{

    // Write something awesome :)
    public function LoginServices($request);
    public function RegisterServices($request);
    public function LogOutServices($request);
    public function ProfileServices($request);
}
