<?php


namespace App\Http\Repositories;

use App\Models\WalletBalance;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class WalletBalanceRepository
{
    private Builder $walletBalance;

    public function __construct(WalletBalance $walletBalance)
    {
        $this->walletBalance = $walletBalance->query();
    }

    /**
     * @param int $memberId
     *
     * @return Builder|Builder[]|Collection|Model|null
     */
    public function find(int $memberId)
    {
        $walletTypes = [
            'roi',
            'bonus',
            'ambc',
            'usdt'
        ];

        return $this->walletBalance->find($memberId, $walletTypes);
    }

}
