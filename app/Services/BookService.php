<?php

namespace App\Services;

use App\ElasticSearch\ElasticSearchQueryBuilderFactory;
use App\Exceptions\BookNotFoundException;
use App\Http\Requests\Books\IndexRequest;
use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookService
{
    public function __construct(private readonly ElasticSearchQueryBuilderFactory $elasticSearchQueryBuilderFactory)
    {
    }

    public function getAllBooks(IndexRequest $indexRequest): LengthAwarePaginator
    {
        $perPage = $indexRequest->integer('per_page', 10);
        $orderBy = $indexRequest->query('order_by', 'created_at');
        $orderType = $indexRequest->query('order_type', 'desc');

        $bookElasticQueryBuilder = $this->elasticSearchQueryBuilderFactory->createBookElasticQueryBuilder();

        return $bookElasticQueryBuilder->build($indexRequest)
            ->orderBy(Book::$sortMapping[$orderBy], $orderType)
            ->paginate($perPage);
    }

    public function getBookById(string $id): Book
    {
        $book = Book::find($id);
        if (! $book) {
            throw new BookNotFoundException();
        }

        return $book->load('category', 'provider');
    }
}
