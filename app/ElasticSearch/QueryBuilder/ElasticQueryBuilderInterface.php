<?php

namespace App\ElasticSearch\QueryBuilder;

use Illuminate\Foundation\Http\FormRequest;
use JeroenG\Explorer\Infrastructure\Scout\Builder;

interface ElasticQueryBuilderInterface
{
    public function build(FormRequest $request): Builder;
}
