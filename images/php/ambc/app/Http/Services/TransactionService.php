<?php


namespace App\Http\Services;


use App\Exceptions\Forbidden;
use App\Exceptions\InternalServerError;
use App\Http\Repositories\MemberBonusTransactionRepository;
use App\Http\Repositories\MemberRoiTransactionRepository;
use App\Http\Repositories\MemberUsdtTransactionRepository;
use App\Http\Repositories\MemberWalletConversionRepository;
use App\Http\Repositories\TransactionTypeRepository;
use App\Http\Repositories\WalletBalanceRepository;
use Exception;

class TransactionService
{
    private TransactionTypeRepository $transactionTypeRepository;
    private MemberRoiTransactionRepository $memberRoiTransactionRepository;
    private MemberBonusTransactionRepository $memberBonusTransactionRepository;
    private MemberUsdtTransactionRepository $memberUsdtTransactionRepository;
    private WalletBalanceRepository $walletBalanceRepository;
    private MemberWalletConversionRepository $memberWalletConversionRepository;

    public function __construct(
        TransactionTypeRepository $transactionTypeRepository,
        MemberRoiTransactionRepository $memberRoiTransactionRepository,
        MemberBonusTransactionRepository $memberBonusTransactionRepository,
        WalletBalanceRepository $walletBalanceRepository,
        MemberUsdtTransactionRepository $memberUsdtTransactionRepository,
        MemberWalletConversionRepository $memberWalletConversionRepository
    )
    {
        $this->transactionTypeRepository        = $transactionTypeRepository;
        $this->memberRoiTransactionRepository   = $memberRoiTransactionRepository;
        $this->memberBonusTransactionRepository = $memberBonusTransactionRepository;
        $this->walletBalanceRepository          = $walletBalanceRepository;
        $this->memberUsdtTransactionRepository  = $memberUsdtTransactionRepository;
        $this->memberWalletConversionRepository = $memberWalletConversionRepository;
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

    /**
     * @param int   $memberId
     * @param float $amount
     *
     * @return array
     * @throws InternalServerError
     */
    public function topUp(int $memberId, float $amount)
    {
        try {

            $transactionType   = $this->transactionTypeRepository->find('topup');
            $transactionTypeId = $transactionType->id;

            $this->memberUsdtTransactionRepository->debit($memberId, $amount, $transactionTypeId);
            $this->walletBalanceRepository->debitUsdt($memberId, $amount);

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
     * @throws Forbidden
     * @throws InternalServerError
     */
    public function withdrawal(int $memberId, float $amount)
    {

        $isBalanceSufficient = $this->walletBalanceRepository
            ->isSufficient($memberId, 'usdt', $amount);

        if ( !$isBalanceSufficient ) {
            throw new Forbidden(
                "INSUFFICIENT_BALANCE",
                config('error.server.CLIENT_EXCEPTION'),
                "the balance of $memberId is insufficient."
            );
        }

        try {
            $transactionType   = $this->transactionTypeRepository->find('withdrawal');
            $transactionTypeId = $transactionType->id;

            $this->memberUsdtTransactionRepository->debit(
                $memberId, $amount, $transactionTypeId
            );
            $this->walletBalanceRepository->debitUsdt($memberId, $amount);

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
     * @param string $type
     * @param float  $amount
     * @param int    $memberId
     *
     * @return array
     * @throws Forbidden
     * @throws InternalServerError
     */
    public function convert(string $type, float $amount, int $memberId)
    {
        $isBalanceSufficient = $this->walletBalanceRepository
            ->isSufficient($memberId, $type, $amount);

        if ( !$isBalanceSufficient ) {
            throw new Forbidden(
                "INSUFFICIENT_BALANCE",
                config('error.server.CLIENT_EXCEPTION'),
                "the $type balance of $memberId is insufficient."
            );
        }

        try {

            $transactionType   = $this->transactionTypeRepository->find('convert');
            $transactionTypeId = $transactionType->id;

            if ( $type === "bonus" ) {
                $senderTransaction = $this->memberBonusTransactionRepository->credit($memberId, $amount, $transactionTypeId);
            }

            if ( $type === "roi" ) {
                $senderTransaction = $this->memberRoiTransactionRepository->credit(
                    $memberId, $amount, $transactionTypeId
                );
            }

            $receiverTransaction = $this->memberUsdtTransactionRepository->debit($memberId, $amount, $transactionTypeId);
            $this->walletBalanceRepository->convert($memberId, $type, $amount);

            $senderTransactionId   = $senderTransaction->id;
            $receiverTransactionId = $receiverTransaction->id;
            $this->memberWalletConversionRepository->create(
                $memberId, $type, $senderTransactionId, $receiverTransactionId
            );

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
