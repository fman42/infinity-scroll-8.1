<?php

namespace Tests\Laravel\Classes;

use Illuminate\Http\Request;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Validator;

class Ioc
{
    public static function validateRequest(Request $request): ?string
    {
        if (method_exists($request, 'rules')) {
            $validator = new Validator(new Translator(new ArrayLoader(), 'en'), $request->all(), $request->rules());
            if ($validator->fails()) {
                return $validator->messages() . '';
            }
        }
        return null;
    }
}