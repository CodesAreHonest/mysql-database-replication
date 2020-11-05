<?php

namespace App\Http\Services;

use App\Exceptions\InternalServerError;
use App\Http\Repositories\MemberRepository;
use Mockery\Exception;

class MemberService
{
    private MemberRepository $memberRepository;

    public function __construct(MemberRepository $memberRepository)
    {
        $this->memberRepository = $memberRepository;
    }

    /**
     * Create a wallet with member
     *
     * @param string $firstName
     * @param string $lastName
     *
     * @return array
     * @throws InternalServerError
     */
    public function register(string $firstName, string $lastName): array
    {

        try {
            $response = $this->memberRepository->createWithWallet($firstName, $lastName);

            return [
                'code'    => 200,
                'message' => 'success',
                'data'    => $response
            ];
        } catch (Exception $exception) {
            throw new InternalServerError($exception);
        }
    }
}
