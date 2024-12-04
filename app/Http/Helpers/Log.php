<?php

namespace App\Http\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\LogLogin;

class Log
{
    public static function LogLogin($log)
    {
        try {
            // Save the log data to the log_login table
            $logEntry = LogLogin::create($log);

            // Optionally, you can return the saved log entry or any other response
            return $logEntry;
        } catch (\Throwable $th) {
            // Handle any exceptions that may occur during the saving process
            return $th;
        }
    }
}
