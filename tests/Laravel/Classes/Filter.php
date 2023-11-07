<?php

namespace Tests\Laravel\Classes;

use Illuminate\Database\Query\Builder;
use InfinityScrollPagination\Laravel\Abstracts\FormRequestDecorator;
use InfinityScrollPagination\Laravel\Abstracts\QueryFilter;
use InfinityScrollPagination\Service\Contracts\Filter as FilterContract;

class Filter extends QueryFilter
{
    public function getDefaultIdentifyKey(?string $identifyKey = null): string
    {
        return $identifyKey ?: 'id';
    }

    public static function apply(Builder $builder, FormRequestDecorator $decorator): Builder
    {
        $self = new self();
        $self->builder = $builder;

        if (($filter = $decorator->getFilter()) && method_exists($self, 'includeFilter')) {
            foreach ($filter as $item) {
                if ($item instanceof FilterContract && !$item->isEmpty()) {
                    $self->includeFilter($item);
                }
            }
        }
        if ($select = $decorator->getSelect()) {
            $self->builder->select($select);
        }

        return $self->builder;
    }
}