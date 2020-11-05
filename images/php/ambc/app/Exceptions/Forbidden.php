<?php


namespace App\Exceptions;


use Illuminate\Http\JsonResponse;
use Exception;

class Forbidden extends Exception {

    private string    $errorMessage;
    private string    $errorDescription;
    private string    $errorCode;
    private int       $statusCode = 403;

    /**
     * Forbidden constructor.
     *
     * @param string|null $errorCode
     * @param string|null $errorDescription
     * @param string|null $errorMessage
     */
    public function __construct(string $errorCode = null,
                                string $errorDescription = null,
                                string $errorMessage = null
    ) {

        $this->errorMessage     = $errorMessage;
        $this->errorCode        = $errorCode;
        $this->errorDescription = $errorDescription;
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