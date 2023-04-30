<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ErrorResource extends JsonResource
{
    protected $responseCode;
    protected $message;

    public function __construct($resource, $message = 'Failed to process request.', $responseCode = 500)
    {
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
            'response_code' => $this->responseCode,
            'message' => $this->message,
            'data' => $this->resource,
        ];
    }
}
