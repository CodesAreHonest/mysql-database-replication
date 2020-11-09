<?php


namespace App\Http\Services;


use App\Exceptions\Forbidden;
use App\Exceptions\InternalServerError;
use App\Http\Repositories\MemberBonusTransactionRepository;
use App\Http\Repositories\MemberRoiTransactionRepository;
use App\Http\Repositories\MemberUsdtTransactionRepository;
use App\Http\Repositories\MemberUsdtTransferTransactionRepository;
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
    private MemberUsdtTransferTransactionRepository $memberUsdtTransferTransactionRepository;

    public function __construct(
        TransactionTypeRepository $transactionTypeRepository,
        MemberRoiTransactionRepository $memberRoiTransactionRepository,
        MemberBonusTransactionRepository $memberBonusTransactionRepository,
        WalletBalanceRepository $walletBalanceRepository,
        MemberUsdtTransactionRepository $memberUsdtTransactionRepository,
        MemberWalletConversionRepository $memberWalletConversionRepository,
        MemberUsdtTransferTransactionRepository $memberUsdtTransferTransactionRepository
    )
    {
        $this->transactionTypeRepository               = $transactionTypeRepository;
        $this->memberRoiTransactionRepository          = $memberRoiTransactionRepository;
        $this->memberBonusTransactionRepository        = $memberBonusTransactionRepository;
        $this->walletBalanceRepository                 = $walletBalanceRepository;
        $this->memberUsdtTransactionRepository         = $memberUsdtTransactionRepository;
        $this->memberWalletConversionRepository        = $memberWalletConversionRepository;
        $this->memberUsdtTransferTransactionRepository = $memberUsdtTransferTransactionRepository;
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

    /**
     * @param int       $senderMemberId
     * @param int       $receiverMemberId
     * @param float     $amount
     * @param float|int $fee
     *
     * @return array
     * @throws InternalServerError|Forbidden
     */
    public function transfer(
        int $senderMemberId,
        int $receiverMemberId,
        float $amount,
        float $fee = 0
    )
    {

        // verify whether is sufficient balance
        $totalDeductionAmount = $amount + $fee;
        $isSufficient         = $this->walletBalanceRepository->isSufficient(
            $senderMemberId,
            'usdt',
            $totalDeductionAmount
        );

        if ( !$isSufficient ) {
            throw new Forbidden(
                "INSUFFICIENT_BALANCE",
                config('error.server.CLIENT_EXCEPTION'),
                "the balance of $senderMemberId is insufficient."
            );
        }

        // transfer transaction type
        $transferTransType = $this->transactionTypeRepository->find('transfer');
        $transferTypeId    = $transferTransType->id;

        // fee transaction type
        $feeTransType   = $this->transactionTypeRepository->find('fee');
        $feeTransTypeId = $feeTransType->id;

        try {
            $senderTransation = $this->memberUsdtTransactionRepository->credit(
                $senderMemberId, $amount, $transferTypeId
            );
            $this->walletBalanceRepository->creditUsdt($senderMemberId, $amount);

            $receiverTransaction = $this->memberUsdtTransactionRepository->debit(
                $receiverMemberId, $amount, $transferTypeId
            );
            $this->walletBalanceRepository->debitUsdt($receiverMemberId, $amount);

            if ( $fee !== 0 ) {
                $senderFeeDeductionTransaction = $this
                    ->memberUsdtTransactionRepository
                    ->credit(
                        $senderMemberId, $fee, $feeTransTypeId
                    );
                $this->walletBalanceRepository->creditUsdt($senderMemberId, $fee);

                $systemMemberId              = config('settings.systemMemberId');
                $systemReceiveFeeTransaction = $this->memberUsdtTransactionRepository
                    ->debit(
                        $systemMemberId, $fee, $feeTransTypeId
                    );
                $this->walletBalanceRepository->debitUsdt($systemMemberId, $fee);
            }

            $senderTransationId    = $senderTransation->id;
            $receiverTransactionId = $receiverTransaction->id;
            $senderFeeDeductionId  = $senderFeeDeductionTransaction->id ?? null;
            $systemReceiveFeeId    = $systemReceiveFeeTransaction->id ?? null;

            $this->memberUsdtTransferTransactionRepository->create(
                $senderMemberId,
                $receiverMemberId,
                $senderTransationId,
                $receiverTransactionId,
                $senderFeeDeductionId,
                $systemReceiveFeeId
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
