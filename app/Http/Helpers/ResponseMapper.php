<?php

namespace App\Http\Helpers;

use App\Http\Helpers\BaseResponse;

class ResponseMapper
{
    public static function Success($data = null, string $message = "Request was successfully", int $code = 200)
    {
        $response = new BaseResponse("Success", $code, $message, $data);

        // Return JSON response
        return response()->json($response, $code);
    }

    public static function Error(string $message, int $code = 400, $data = null)
    {
        $response = new BaseResponse("Error", $code, $message, $data);

        // Return JSON response
        return response()->json($response, $code);
    }

    public static function Unauthorized(string $message = "Unauthorized access")
    {
        return self::Error($message, 401);
    }

    public static function InternalServerError(string $message = "An unexpected error occurred", $data = null)
    {
        return self::Error($message, 500, $data);
    }

    public static function NotFound(string $message = "Resource not found")
    {
        return self::Error($message, 404);
    }

    public static function Created($data = null, string $message = "Resource successfully created")
    {
        $response = new BaseResponse("Created", 201, $message, $data);

        // Return JSON response
        return response()->json($response, 201);
    }
}

?>
