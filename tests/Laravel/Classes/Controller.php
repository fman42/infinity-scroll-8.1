<?php

namespace Tests\Laravel\Classes;

use Illuminate\Http\Response;
use InfinityScrollPagination\Laravel\Abstracts\Resource;

class Controller
{
    public function __invoke(FilterRequest $request): Response
    {
        return Resource::enumerable(
            Filter::apply(User::query(), $request)->cursor()
        )->get($request);
    }
}