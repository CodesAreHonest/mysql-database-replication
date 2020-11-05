<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\MessageBag;

class UnprocessableEntity extends Exception {

    private array                    $errorMessage;
    private string                   $errorCode;
    private string                   $errorDescription;
    private int                      $statusCode = 422;

    /**
     * UnprocessableEntityException constructor.
     *
     * @param string $errorCode
     * @param string $errorDescription
     * @param array  $errorMessage
     */
    public function __construct(string $errorCode, string $errorDescription, array $errorMessage) {

        $this->errorMessage     = $errorMessage;
        $this->errorDescription = $errorDescription;
        $this->errorCode        = $errorCode;
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse {

        $returnParams = [
            'status' => $this->statusCode,
        ];

        if ($this->errorCode) {
            $returnParams['errorCode'] = strtoupper($this->errorCode);
        }

        if ($this->errorDescription) {
            $returnParams['errorDescription'] = $this->errorDescription;
        }

        if ($this->errorMessage) {
            $returnParams['errorMessage'] = $this->errorMessage;
        }

        return response()->json($returnParams, $this->statusCode);
    }
}
