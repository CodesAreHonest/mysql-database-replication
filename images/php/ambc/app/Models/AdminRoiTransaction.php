<?php

namespace App\Models;

use App\Model\MemberRoiTransaction;
use Illuminate\Database\Eloquent\Model;

class AdminRoiTransaction extends Model
{
    protected $table = 'admin_roi_transaction';

    protected $fillable = [
        'member_roi_transaction_id',
        'remarks'
    ];

    public function memberRoiTransaction()
    {
        return $this->hasOne(MemberRoiTransaction::class, 'member_roi_transaction_id', 'id');
    }
}
