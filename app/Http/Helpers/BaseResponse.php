<?php

namespace App\Http\Helpers;

class BaseResponse
{
    public $status;
    public $code;
    public $message;
    public $data;

    public function __construct(string $status, int $code, string $message, $data = null)
    {
        $this->status = $status;
        $this->code = $code;
        $this->message = $message;
        $this->data = $data;
    }

    public function jsonSerialize()
    {
        return [
            'status' => $this->status,
            'code' => $this->code,
            'message' => $this->message,
            'data' => $this->data
        ];
    }
}
