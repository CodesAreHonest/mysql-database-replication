<?php

namespace App\Http\Cache;

use Illuminate\Support\Facades\Cache;
use App\Http\Repositories\WalletBalanceRepository;

class WalletBalanceCache
{
    private string $cacheKey;
    private WalletBalanceRepository $walletBalanceRepository;

    public function __construct(
        WalletBalanceRepository $walletBalanceRepository
    ) {
        $this->cacheKey = "wallet-balance";
        $this->walletBalanceRepository = $walletBalanceRepository;
    }

    /**
     * @param int $memberId
     * @param $data
     *
     * @return bool
     */
    public function put(int $memberId): bool
    {
        $memberBalance = $this->walletBalanceRepository->find($memberId);
        return Cache::put("$this->cacheKey@$memberId", $memberBalance);
    }

    /**
     * @param int $memberId
     *
     * @return bool
     */
    public function has(int $memberId): bool
    {
        return Cache::has("$this->cacheKey@$memberId");
    }

    /**
     * @param int $memberId
     * @param Closure
     *
     * @return bool
     */
    public function get(int $memberId)
    {
        return Cache::get("$this->cacheKey@$memberId");
    }
}
