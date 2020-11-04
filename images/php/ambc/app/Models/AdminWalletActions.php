<?php

namespace App\Models;

use App\Model\WalletBalance;
use Illuminate\Database\Eloquent\Model;

class AdminWalletActions extends Model
{
    protected $table = 'admin_wallet_actions';

    protected $fillable = [
        'wallet_balance_id',
        'admin_updated_at',
        'admin_id',
        'remarks'
    ];

    public function walletBalance()
    {
        return $this->hasOne(WalletBalance::class);
    }

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }
}
