<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    protected $httpCode;
    protected $responseCode;
    protected $message;

    public function __construct($resource, $message = 'Failed to process request.', $responseCode = 2000, $httpCode = 500)
    {
        $this->httpCode = $httpCode;
        $this->responseCode = $responseCode;
        $this->message = $message;

        parent::__construct($resource);
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => false,
            'http_code' => $this->httpCode,
            'response_code' => $this->responseCode,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
