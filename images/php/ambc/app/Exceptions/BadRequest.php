<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BadRequest extends Exception {

    private string        $errorMessage;
    private string        $errorCode;
    private string        $errorDescription;
    private               $additionalData;
    private int           $statusCode = 400;

    /**
     * BadRequest constructor.
     *
     * @param string|null $errorCode
     * @param string|null $errorDescription
     * @param string|null $errorMessage
     */
    public function __construct(string $errorCode = "",
                                string $errorDescription = "",
                                string $errorMessage = "",
                                $additionalData = null
    ) {
        $this->errorMessage     = $errorMessage;
        $this->errorCode        = $errorCode;
        $this->errorDescription = $errorDescription;
        $this->additionalData   = $additionalData;
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

        if ($this->additionalData) {
            $returnParams['data'] = $this->additionalData;
        }

        return response()->json($returnParams, $this->statusCode);
    }
}
