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
     *
     * @see
     * -> Filter: (transaction_type.deleted_at is null)  (cost=0.26 rows=0) (actual time=0.049..0.053 rows=1 loops=1)
     * -> Index lookup on transaction_type using transaction_type_name_index (name='fee')  (cost=0.26 rows=1) (actual time=0.047..0.051 rows=1 loops=1)
     */
    public function find(string $key)
    {
        $selectQuery = $this->transactionType
            ->setConnection("mysql::read")
            ->query();

        $transactionType = $selectQuery
            ->select(['id', 'name'])
            ->where('name', $key)
            ->first();

        return $transactionType;
    }
}
