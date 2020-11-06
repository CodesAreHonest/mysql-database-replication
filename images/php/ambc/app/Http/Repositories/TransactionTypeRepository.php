<?php


namespace App\Http\Repositories;


use App\Models\TransactionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransactionTypeRepository
{
    private Builder $transactionType;

    public function __construct(TransactionType $transactionType)
    {
        $this->transactionType = $transactionType->query();
    }

    /**
     * @param string $key
     *
     * @return Builder|Model|object|null
     */
    public function find(string $key)
    {
        $transactionType = $this->transactionType
            ->where('name', $key)
            ->first();

        return $transactionType;
    }
}
