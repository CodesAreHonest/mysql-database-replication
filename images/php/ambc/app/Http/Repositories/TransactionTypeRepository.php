<?php


namespace App\Http\Repositories;


use App\Models\TransactionType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class TransactionTypeRepository
{
    private TransactionType $transactionType;

    public function __construct(TransactionType $transactionType)
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @param string $key
     *
     * @return Builder|Model|object|null
     */
    public function find(string $key)
    {
        $selectQuery = $this->transactionType
            ->setConnection("mysql::read")
            ->query();

        $transactionType = $selectQuery
            ->where('name', $key)
            ->first();

        return $transactionType;
    }
}
