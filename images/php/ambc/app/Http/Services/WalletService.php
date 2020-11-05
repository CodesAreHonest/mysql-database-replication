<?php


namespace App\Http\Services;

use App\Exceptions\InternalServerError;
use App\Http\Repositories\WalletBalanceRepository;
use Exception;

class WalletService
{
    private WalletBalanceRepository $walletBalanceRepository;

    public function __construct(
        WalletBalanceRepository $walletBalanceRepository
    )
    {
        $this->walletBalanceRepository = $walletBalanceRepository;
    }

    /**
     * @param int $memberId
     *
     * @return array
     * @throws InternalServerError
     */
    public function getBalance(int $memberId)
    {
        try {
            $memberBalances = $this->walletBalanceRepository->find($memberId);

            return [
                'code'    => 200,
                'message' => 'success',
                'data'    => $memberBalances
            ];
        } catch (Exception $exception) {
            throw new InternalServerError(
                "SERVER_EXCEPTION",
                config('error.server.SERVER_EXCEPTION'),
                $exception->getMessage()
            );
        }

    }
}
