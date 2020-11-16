<?php

namespace App\Http\Cache;

use Illuminate\Support\Facades\Cache;

class WalletBalanceCache
{
    private string $key = "wallet-balance";

    /**
     * @param int $memberId
     * @param $data
     *
     * @return bool
     */
    public function put(int $memberId, $data): bool
    {
        return Cache::put("$this->key@$memberId", $data);
    }

    /**
     * @param int $memberId
     *
     * @return bool
     */
    public function has(int $memberId): bool
    {
        return Cache::has("$this->key@$memberId");
    }

    /**
     * @param int $memberId
     *
     * @return bool
     */
    public function get(int $memberId)
    {
        return Cache::get("$this->key@$memberId");
    }

    /**
     * Forget the specific cache key
     *
     * @param  mixed $memberId
     * @return bool
     */
    public function forget(int $memberId)
    {
        return Cache::forget("$this->key@$memberId");
    }
}
