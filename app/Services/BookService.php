<?php

namespace App\Services;

use App\ElasticSearch\ElasticSearchQueryBuilderFactory;
use App\Exceptions\BookNotFoundException;
use App\Http\Requests\Books\IndexRequest;
use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookService
{
    public static array $sortMapping = [
        'title' => 'slug',
        'author' => 'author',
        'created_at' => 'created_at',
        'updated_at' => 'updated_at',
    ];

    public function __construct(private readonly ElasticSearchQueryBuilderFactory $elasticSearchQueryBuilderFactory)
    {
    }

    public function getAllBooks(int $perPage = 10, ?string $query = null, string $orderBy = 'created_at', string $orderType = 'desc'): LengthAwarePaginator
    {
        $books = Book::orderBy($orderBy, $orderType);
        if ($query) {
            $books->where('title', 'like', "%{$query}%")
                ->orWhere('author', 'like', "%{$query}%")
                ->orWhere('book_number', 'like', "%{$query}%")
                ->orWhereHas('category', function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%");
                });
        }

        return $books->paginate($perPage);
    }

    public function search(IndexRequest $indexRequest): LengthAwarePaginator
    {
        $perPage = $indexRequest->integer('per_page', 10);
        $orderBy = $indexRequest->query('order_by', 'created_at');
        $orderType = $indexRequest->query('order_type', 'desc');

        $bookElasticQueryBuilder = $this->elasticSearchQueryBuilderFactory->createBookElasticQueryBuilder();

        return $bookElasticQueryBuilder->build($indexRequest)
            ->orderBy(self::$sortMapping[$orderBy], $orderType)
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
