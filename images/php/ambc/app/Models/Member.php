<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected $table = 'members';

    protected $fillable = [
        'first_name',
        'last_name'
    ];

    public function walletBalance()
    {
        return $this->hasOne(
            WalletBalance::class,
            'member_id',
            'id'
        );
    }
}
