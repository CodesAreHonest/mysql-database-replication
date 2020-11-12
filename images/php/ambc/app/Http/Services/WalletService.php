<?php


namespace App\Http\Services;

use App\Exceptions\InternalServerError;
use App\Http\Repositories\WalletBalanceRepository;
use App\Http\Cache\WalletBalanceCache;
use Exception;

class WalletService
{
    private WalletBalanceRepository $walletBalanceRepository;
    private WalletBalanceCache $walletBalanceCache;

    public function __construct(
        WalletBalanceRepository $walletBalanceRepository,
        WalletBalanceCache $walletBalanceCache
    ) {
        $this->walletBalanceRepository = $walletBalanceRepository;
        $this->walletBalanceCache = $walletBalanceCache;
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
            $isCacheExist = $this->walletBalanceCache->has($memberId);
            if ($isCacheExist) {
                $cacheResults = $this->walletBalanceCache->get($memberId);
                return [
                    'code'    => 200,
                    'message' => 'success',
                    'data'    => $cacheResults
                ];
            }

            $memberBalances = $this->walletBalanceRepository->find($memberId);
            $this->walletBalanceCache->put($memberId, $memberBalances);

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
