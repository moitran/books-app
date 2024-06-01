<?php

namespace App\ElasticSearch\QueryBuilder;

use Illuminate\Foundation\Http\FormRequest;
use Laravel\Scout\Builder;

interface ElasticQueryBuilderInterface
{
    public function build(FormRequest $request): Builder;
}
