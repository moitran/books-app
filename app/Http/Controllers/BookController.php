<?php

namespace App\Http\Controllers;

use App\Http\Requests\Books\IndexRequest;
use App\Http\Resources\BookResource;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookController extends Controller
{
    public function __construct(private readonly BookService $bookService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request): JsonResource
    {
        $perPage = $request->integer('per_page', 10);
        $query = $request->query('query');
        $orderBy = $request->query('order_by', 'created_at');
        $orderType = $request->query('order_type', 'desc');
        $books = $this->bookService->getAllBooks($perPage, $query, $orderBy, $orderType);

        return BookResource::collection($books);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return BookResource::make($this->bookService->getBookById($id));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
