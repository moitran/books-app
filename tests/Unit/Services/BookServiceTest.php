<?php

namespace Tests\Unit\Services;

use App\ElasticSearch\ElasticSearchQueryBuilderFactory;
use App\ElasticSearch\QueryBuilder\BookElasticQueryBuilder;
use App\Exceptions\BookNotFoundException;
use App\Http\Requests\Books\IndexRequest;
use App\Models\Book;
use App\Services\BookService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class BookServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_get_all_books()
    {
        $mockBook = Mockery::mock('alias:App\Models\Book');
        $mockBook->shouldReceive('orderBy')
            ->once()
            ->with('created_at', 'desc')
            ->andReturnSelf();
        $mockBook->shouldReceive('paginate')
            ->once()
            ->with(10)
            ->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $bookService = new BookService(Mockery::mock(ElasticSearchQueryBuilderFactory::class));
        $result = $bookService->getAllBooks();

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_get_all_books_with_query()
    {
        $query = 'test query';
        $mockBook = Mockery::mock('alias:App\Models\Book');
        $mockBook->shouldReceive('orderBy')
            ->once()
            ->with('created_at', 'desc')
            ->andReturnSelf();
        $mockBook->shouldReceive('where')
            ->once()
            ->with('title', 'like', "%{$query}%")
            ->andReturnSelf();
        $mockBook->shouldReceive('orWhere')
            ->once()
            ->with('author', 'like', "%{$query}%")
            ->andReturnSelf();
        $mockBook->shouldReceive('orWhere')
            ->once()
            ->with('book_number', 'like', "%{$query}%")
            ->andReturnSelf();
        $mockBook->shouldReceive('orWhereHas')
            ->once()
            ->with('category', Mockery::on(function ($callback) use ($query) {
                $mockQuery = Mockery::mock();
                $mockQuery->shouldReceive('where')
                    ->once()
                    ->with('name', 'like', "%{$query}%")
                    ->andReturnSelf();
                $callback($mockQuery);

                return true;
            }))
            ->andReturnSelf();
        $mockBook->shouldReceive('paginate')
            ->once()
            ->with(10)
            ->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $bookService = new BookService(Mockery::mock(ElasticSearchQueryBuilderFactory::class));
        $result = $bookService->getAllBooks(10, $query);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_search()
    {
        $mockIndexRequest = Mockery::mock(IndexRequest::class);
        $mockIndexRequest->shouldReceive('integer')->with('per_page', 10)->andReturn(10);
        $mockIndexRequest->shouldReceive('query')->with('order_by', 'created_at')->andReturn('created_at');
        $mockIndexRequest->shouldReceive('query')->with('order_type', 'desc')->andReturn('desc');

        $mockElasticQueryBuilderFactory = Mockery::mock(ElasticSearchQueryBuilderFactory::class);
        $mockBookElasticQueryBuilder = Mockery::mock(BookElasticQueryBuilder::class);
        $mockScoutBuilder = Mockery::mock('JeroenG\Explorer\Infrastructure\Scout\Builder');

        $mockBookElasticQueryBuilder->shouldReceive('build')->with($mockIndexRequest)->andReturn($mockScoutBuilder);
        $mockScoutBuilder->shouldReceive('orderBy')->with('created_at', 'desc')->andReturnSelf();
        $mockScoutBuilder->shouldReceive('paginate')->with(10)->andReturn(Mockery::mock(LengthAwarePaginator::class));

        $mockElasticQueryBuilderFactory->shouldReceive('createBookElasticQueryBuilder')->andReturn($mockBookElasticQueryBuilder);

        $bookService = new BookService($mockElasticQueryBuilderFactory);
        $result = $bookService->search($mockIndexRequest);

        $this->assertInstanceOf(LengthAwarePaginator::class, $result);
    }

    public function test_get_book_by_id()
    {
        $mockBook = Mockery::mock('alias:App\Models\Book');
        $mockBook->shouldReceive('find')->with('1')->andReturn($mockBook);
        $mockBook->shouldReceive('load')->with('category', 'provider')->andReturnSelf();

        $bookService = new BookService(Mockery::mock(ElasticSearchQueryBuilderFactory::class));
        $result = $bookService->getBookById('1');

        $this->assertInstanceOf(Book::class, $result);
    }

    public function test_get_book_by_id_throw_exception()
    {
        $this->expectException(BookNotFoundException::class);

        $mockBook = Mockery::mock('alias:App\Models\Book');
        $mockBook->shouldReceive('find')->with('invalid_id')->andReturn(null);

        $bookService = new BookService(Mockery::mock(ElasticSearchQueryBuilderFactory::class));
        $bookService->getBookById('invalid_id');
    }
}
