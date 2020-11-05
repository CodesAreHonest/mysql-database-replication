<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class MissingScopeException extends Exception
{
    /**
     * The scopes that the user did not have.
     *
     * @var array
     */
    protected $scopes;

    private string $errorMessage;

    /**
     * Create a new missing scope exception.
     *
     * @param  array|string  $scopes
     * @param  string  $message
     * @return void
     */
    public function __construct($scopes = [], $message = 'Invalid scope(s) provided.')
    {
        $this->errorMessage = $message;
        $this->scopes = Arr::wrap($scopes);
    }

    /**
     * @return JsonResponse
     */
    public function render(): JsonResponse {

        $returnParams = [
            'status' => 403,
            'errorCode' => 'UNAUTHORIZED',
            'errorDescription' => config('error.server.UNAUTHORIZED'),
            'errorMessage' => $this->errorMessage,
            'scopesRequired' => $this->scopes
        ];

        return response()->json($returnParams, 403);
    }
}
