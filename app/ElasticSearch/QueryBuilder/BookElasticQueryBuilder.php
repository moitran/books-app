<?php

namespace App\ElasticSearch\QueryBuilder;

use App\Http\Requests\Books\IndexRequest;
use App\Models\Book;
use Illuminate\Foundation\Http\FormRequest;
use InvalidArgumentException;
use JeroenG\Explorer\Domain\Syntax\Matching;
use JeroenG\Explorer\Domain\Syntax\Nested;
use JeroenG\Explorer\Domain\Syntax\Term;
use Laravel\Scout\Builder;

class BookElasticQueryBuilder implements ElasticQueryBuilderInterface
{
    public function build(FormRequest $request): Builder
    {
        if (! $request instanceof IndexRequest) {
            throw new InvalidArgumentException('Request must be an instance of IndexRequest');
        }

        $query = $request->query('query');
        $categoryId = $request->query('category_id');
        $providerId = $request->query('provider_id');

        $books = Book::search();

        if ($query) {
            $books->filter(
                new Matching('title', $query)
            );
        }

        if ($categoryId) {
            $books->must(new Nested('category', new Term('category.id', $categoryId)));
        }

        if ($providerId) {
            $books->must(new Nested('provider', new Term('provider.id', $providerId)));
        }

        return $books;
    }
}
