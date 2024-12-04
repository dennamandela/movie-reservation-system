<?php

namespace App\Repositories\User;

use LaravelEasyRepository\Repository;

interface UserRepository extends Repository{

     // Write something awesome :)
     public function findByEmail($email);
     public function create($data);
     public function getAllUser();
     public function getPaginatedUser($perPage = 10);
     public function getUserById($id);
    }
