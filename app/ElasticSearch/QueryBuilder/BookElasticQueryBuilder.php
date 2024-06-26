<?php

namespace App\ElasticSearch\QueryBuilder;

use App\Http\Requests\Books\IndexRequest;
use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;
use JeroenG\Explorer\Domain\Query\QueryProperties\TrackTotalHits;
use JeroenG\Explorer\Domain\Syntax\Compound\BoolQuery;
use JeroenG\Explorer\Domain\Syntax\Matching;
use JeroenG\Explorer\Domain\Syntax\Nested;
use JeroenG\Explorer\Domain\Syntax\Term;
use JeroenG\Explorer\Infrastructure\Scout\Builder;

class BookElasticQueryBuilder implements ElasticQueryBuilderInterface
{
    public function build(FormRequest $request): Builder
    {
        if (!$request instanceof IndexRequest) {
            throw new InvalidArgumentException('Request must be an instance of IndexRequest');
        }

        $query = $request->query('query');
        $categoryId = $request->query('category_id');
        $providerId = $request->query('provider_id');

        /** @var Builder $builder */
        $builder = Book::search();

        if ($query) {
            $bool = new BoolQuery;
            // title match query OR author match query OR book_number match query
            $bool->should(new Matching('title', $query));
            $bool->should(new Matching('author', $query));
            $bool->should(new Matching('book_number', $query));
            $builder->filter($bool);
        }

        if ($categoryId) {
            $builder->must(new Nested('category', new Term('category.id', $categoryId)));
        }

        if ($providerId) {
            $builder->must(new Nested('provider', new Term('provider.id', $providerId)));
        }

        $builder->property(TrackTotalHits::all());

        return $builder;
    }
}
