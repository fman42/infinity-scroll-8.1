<?php

namespace InfinityScrollPagination\Laravel\Abstracts;

use Illuminate\Foundation\Http\FormRequest;
use InfinityScrollPagination\Service\Contracts\Filter;
use InfinityScrollPagination\Service\Implementation\Request;

/**
 * @method string|null getIdentifyKey();
 * @method void setIdentifyKey(string $identifyKey);
 * @method int|null getTo();
 * @method void setTo(int $to);
 * @method array|null getFilter()
 * @method void setFilter(array $filter)
 * @method void addFilter(Filter $filter)
 * @method void setPage(int $page)
 * @method int|null getPage()
 * @method void setNextIdentify(string $nextIdentify)
 * @method string|null getNextIdentify()
 * @method void setSelect(array $select)
 * @method void addSelect(string $select)
 * @method array|null getSelect()
 */
abstract class FormRequestDecorator extends FormRequest
{
    private ?Request $_request = null;

    final public function __construct()
    {
        parent::__construct(...func_get_args());
        $this->init();
    }

    final public function __call($method, $parameters)
    {
        if (!$this->_request) {
            $this->_request = new Request($this->all());
        }
        return $this->_request->{$method}(...$parameters);
    }

    public function toArray(): array
    {
        if (!$this->_request) {
            $this->_request = new Request($this->all());
        }
        return $this->_request->toArray();
    }

    protected function init() {}
}