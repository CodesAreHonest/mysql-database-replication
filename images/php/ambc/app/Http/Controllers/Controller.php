<?php

namespace App\Http\Controllers;

use App\Exceptions\UnprocessableEntity;
use Illuminate\Support\Facades\Validator;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * @param array $request
     * @param array $rules
     *
     * @throws UnprocessableEntity
     */
    protected function validator(array $request, array $rules)
    {

        $validator = Validator::make($request, $rules);

        if ($validator->fails()) {

            throw new UnprocessableEntity(
                "INVALID_INPUT",
                config('error.validation.INVALID_PAYLOAD'),
                $validator->errors()->toArray()
            );
        }
    }

    /**
     * @param array $request
     * @param array $rules
     *
     * @throws UnprocessableEntity
     */
    protected function customValidator(array $request, array $rules)
    {
        $validator = Validator::make($request, $rules);

        if ($validator->fails()) {

            throw new UnprocessableEntity(
                "INVALID_INPUT",
                config('error.validation.INVALID_PAYLOAD'),
                $validator->failed()
            );
        }
    }
}
