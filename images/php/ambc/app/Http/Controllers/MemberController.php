<?php

namespace App\Http\Controllers;

use App\Exceptions\BadGateway;
use App\Exceptions\InternalServerError;
use App\Exceptions\UnprocessableEntity;
use App\Http\Services\MemberService;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MemberController extends Controller
{
    private MemberService $memberService;

    public function __construct(MemberService $memberService)
    {
        $this->memberService = $memberService;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     * @throws BadGateway
     * @throws InternalServerError
     * @throws UnprocessableEntity
     */
    public function register(Request $request)
    {
        $rules = [
            'firstName' => 'required|string|max:100',
            'lastName'  => 'required|string|max:100'
        ];

        $this->validator($request->all(), $rules);

        $firstName = $request->input('firstName');
        $lastName  = $request->input('lastName');

        $response = $this->memberService->registerAccount($firstName, $lastName);

        switch ($response['code']) {
            case 200:
                return response()->json($response, 200);
            case 502:
                throw new BadGateway();
        }
    }
}
