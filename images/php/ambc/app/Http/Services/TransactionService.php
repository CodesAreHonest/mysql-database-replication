<?php


namespace App\Http\Services;


use App\Exceptions\InternalServerError;
use App\Http\Repositories\MemberBonusTransactionRepository;
use App\Http\Repositories\MemberRoiTransactionRepository;
use App\Http\Repositories\TransactionTypeRepository;
use App\Http\Repositories\WalletBalanceRepository;
use Exception;

class TransactionService
{
    private TransactionTypeRepository $transactionTypeRepository;
    private MemberRoiTransactionRepository $memberRoiTransactionRepository;
    private MemberBonusTransactionRepository $memberBonusTransactionRepository;
    private WalletBalanceRepository $walletBalanceRepository;

    public function __construct(
        TransactionTypeRepository $transactionTypeRepository,
        MemberRoiTransactionRepository $memberRoiTransactionRepository,
        MemberBonusTransactionRepository $memberBonusTransactionRepository,
        WalletBalanceRepository $walletBalanceRepository
    )
    {
        $this->transactionTypeRepository        = $transactionTypeRepository;
        $this->memberRoiTransactionRepository   = $memberRoiTransactionRepository;
        $this->memberBonusTransactionRepository = $memberBonusTransactionRepository;
        $this->walletBalanceRepository          = $walletBalanceRepository;
    }

    /**
     * @param int   $memberId
     * @param float $amount
     *
     * @return array
     * @throws InternalServerError
     */
    public function debitRoi(int $memberId, float $amount)
    {
        try {
            $transactionType   = $this->transactionTypeRepository->find('roi');
            $transactionTypeId = $transactionType->id;

            $this->memberRoiTransactionRepository->debit($memberId, $amount, $transactionTypeId);
            $this->walletBalanceRepository->debitRoi($memberId, $amount);

            return [
                'code'    => 200,
                'message' => 'success'
            ];
        } catch (Exception $exception) {
            throw new InternalServerError(
                'INTERNAL_SERVER_ERROR',
                config('error.server.SERVER_EXCEPTION'),
                $exception->getMessage()
            );
        }
    }

    /**
     * @param int   $memberId
     * @param float $amount
     *
     * @return array
     * @throws InternalServerError
     */
    public function debitBonus(int $memberId, float $amount)
    {
        try {

            $transactionType   = $this->transactionTypeRepository->find('bonus');
            $transactionTypeId = $transactionType->id;

            $this->memberBonusTransactionRepository->debit($memberId, $amount, $transactionTypeId);
            $this->walletBalanceRepository->debitBonus($memberId, $amount);

            return [
                'code'    => 200,
                'message' => 'success'
            ];
        } catch (Exception $exception) {
            throw new InternalServerError(
                'INTERNAL_SERVER_ERROR',
                config('error.server.SERVER_EXCEPTION'),
                $exception->getMessage()
            );
        }

    }

}
