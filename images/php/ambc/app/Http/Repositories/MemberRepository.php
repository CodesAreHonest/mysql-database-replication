<?php

namespace App\Http\Repositories;

use App\Models\Member;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MemberRepository
{
    private Builder $member;

    public function __construct(Member $member)
    {
        $this->member = $member->query();
    }

    /**
     * @param string $firstName
     * @param string $lastName
     *
     * @return Builder|Model
     */
    public function createWithWallet(string $firstName, string $lastName)
    {
        $memberAttributes = [
            'first_name' => $firstName,
            'last_name'  => $lastName
        ];

        $walletBalanceAttributes = [
            'roi'   => 0,
            'bonus' => 0,
            'ambc'  => 0,
            'usdt'  => 0
        ];

        $newMember = $this->member->create($memberAttributes);
        $newMember->walletBalance()->create($walletBalanceAttributes);

        return $newMember;
    }
}
