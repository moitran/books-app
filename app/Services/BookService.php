<?php

namespace App\Services;

use App\Exceptions\BookNotFoundException;
use App\Models\Book;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BookService
{
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

    public function getBookById(string $id): Book
    {
        $book = Book::find($id);
        if (! $book) {
            throw new BookNotFoundException();
        }

        return $book->load('category', 'provider');
    }
}
