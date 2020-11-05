<?php

namespace App\Http\Controllers;

use App\Exceptions\BadGateway;
use App\Exceptions\UnprocessableEntity;
use App\Exceptions\InternalServerError;
use App\Http\Services\WalletService;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws BadGateway
     * @throws UnprocessableEntity
     * @throws InternalServerError
     */
    public function balance(Request $request)
    {
        $rules = [
            'memberId' => 'required|int|exists:wallet_balances,member_id',
        ];

        $this->validator($request->all(), $rules);

        $memberId = (int)$request->input('memberId');

        $response = $this->walletService->getBalance($memberId);

        switch ($response['code']) {
            case 200:
                return response()->json($response, 200);
            default:
                throw new BadGateway();
        }

    }
}
