<?php

namespace InfinityScrollPagination\Laravel\Implementation\Request;

use InfinityScrollPagination\Laravel\Abstracts\FormRequestDecorator;

class FilterRequest extends FormRequestDecorator
{
    public function rules(): array
    {
        return [
            'to' => ['required_with:page'],
            'nextIdentify' => $this->input('nextIdentify', null) == null ? [] : ['required'],
            'filter' => ['array'],
            'select' => ['array'],
        ];
    }

    public function messages(): array
    {
        return [];
    }
}