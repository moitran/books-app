<?php

namespace App\ElasticSearch;

use App\ElasticSearch\QueryBuilder\BookElasticQueryBuilder;
use App\ElasticSearch\QueryBuilder\ElasticQueryBuilderInterface;

class ElasticSearchQueryBuilderFactory
{
    public function createBookElasticQueryBuilder(): ElasticQueryBuilderInterface
    {
        return new BookElasticQueryBuilder();
    }
}
