<?php


namespace App\Exceptions;


use Illuminate\Http\JsonResponse;
use Exception;

class Unauthenticated extends Exception
{
    private ?string    $errorMessage;
    private ?string    $errorCode;
    private ?string    $errorDescription;
    private int       $statusCode = 401;

    /**
     * InternalServerError constructor.
     *
     * @param string|null $errorCode
     * @param string|null $errorMessage
     */
    public function __construct(
        ?string $errorCode = null,
        ?string $errorDescription = null,
        ?string $errorMessage = null
    ) {

        $this->errorMessage     = $errorMessage;
        $this->errorDescription = $errorDescription;
        $this->errorCode        = $errorCode;
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {

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