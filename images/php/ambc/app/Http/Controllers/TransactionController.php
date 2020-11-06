<?php

namespace App\Http\Controllers;

use App\Exceptions\BadGateway;
use App\Exceptions\Forbidden;
use App\Exceptions\InternalServerError;
use App\Exceptions\UnprocessableEntity;
use App\Http\Services\TransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws BadGateway
     * @throws InternalServerError
     * @throws UnprocessableEntity
     */
    public function roi(Request $request)
    {
        $rules = [
            'memberId' => 'required|int|exists:members,id',
            'amount'   => 'required|numeric|min:0|not_in:0'
        ];

        $this->validator($request->all(), $rules);

        $memberId = (int)$request->input('memberId');
        $amount   = (float)$request->input('amount');

        $response = $this->transactionService->debitRoi(
            $memberId, $amount
        );

        switch ($response['code']) {
            case 200:
                return response()->json($response, 200);
            default:
                throw new BadGateway();
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws BadGateway
     * @throws InternalServerError
     * @throws UnprocessableEntity
     */
    public function bonus(Request $request)
    {
        $rules = [
            'memberId' => 'required|int|exists:members,id',
            'amount'   => 'required|numeric|min:0|not_in:0'
        ];

        $this->validator($request->all(), $rules);

        $memberId = (int)$request->input('memberId');
        $amount   = (float)$request->input('amount');

        $response = $this->transactionService->debitBonus(
            $memberId, $amount
        );

        switch ($response['code']) {
            case 200:
                return response()->json($response, 200);
            default:
                throw new BadGateway();
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws BadGateway
     * @throws InternalServerError
     * @throws UnprocessableEntity
     */
    public function topUp(Request $request)
    {
        $rules = [
            'memberId' => 'required|int|exists:members,id',
            'amount'   => 'required|numeric|min:0|not_in:0'
        ];

        $this->validator($request->all(), $rules);

        $memberId = (int)$request->input('memberId');
        $amount   = (float)$request->input('amount');

        $response = $this->transactionService->topUp($memberId, $amount);

        switch ($response['code']) {
            case 200:
                return response()->json($response, 200);
            default:
                throw new BadGateway();
        }
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws BadGateway
     * @throws InternalServerError
     * @throws UnprocessableEntity
     * @throws Forbidden
     */
    public function withdrawal(Request $request)
    {
        $rules = [
            'memberId' => 'required|int|exists:members,id',
            'amount'   => 'required|numeric|min:0|not_in:0'
        ];

        $this->validator($request->all(), $rules);

        $memberId = (int)$request->input('memberId');
        $amount   = (float)$request->input('amount');

        $response = $this->transactionService->withdrawal($memberId, $amount);

        switch ($response['code']) {
            case 200:
                return response()->json($response, 200);
            default:
                throw new BadGateway();
        }
    }
}
