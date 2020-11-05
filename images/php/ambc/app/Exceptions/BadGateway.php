<?php


namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BadGateway extends Exception {

    private string    $errorMessage;
    private string    $errorDescription;
    private string    $errorCode;
    private int       $statusCode = 502;

    /**
     * BadGatewayException constructor.
     *
     * @param string|null $errorCode
     * @param string|null $errorDescription
     * @param string|null $errorMessage
     */
    public function __construct(string $errorCode = "",
                                string $errorDescription = "",
                                string $errorMessage = ""
    ) {
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
