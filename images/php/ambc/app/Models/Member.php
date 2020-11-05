<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use SoftDeletes;

    protected string $table = 'members';

    protected array $fillable = [
        'first_name',
        'last_name '
    ];

    public function walletBalance()
    {
        return $this->belongsTo(WalletBalance::class);
    }
}
