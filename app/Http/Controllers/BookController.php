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
     *
     * @OA\Get(
     *     path="/api/books",
     *     summary="Get a list of books",
     *     tags={"Books"},
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Number of page",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Number of page",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search query",
     *         required=false,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         description="Field to order by",
     *         required=false,
     *
     *         @OA\Schema(type="string", enum={"title", "author", "created_at", "updated_at"}, default="created_at")
     *     ),
     *
     *     @OA\Parameter(
     *         name="order_type",
     *         in="query",
     *         description="Order type (ascending or descending)",
     *         required=false,
     *
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/BookResource")
     *         )
     *     )
     * )
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
     * Display a listing of the resource.
     *
     * @OA\Get(
     *     path="/api/books/search",
     *     summary="Get a list of books",
     *     tags={"Books"},
     *
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Number of page",
     *         required=false,
     *
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="query",
     *         in="query",
     *         description="Search query",
     *         required=false,
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="category_id",
     *         in="query",
     *         description="Search by Category",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Parameter(
     *         name="provider_id",
     *         in="query",
     *         description="Search by Provider",
     *         required=false,
     *
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Parameter(
     *         name="order_by",
     *         in="query",
     *         description="Field to order by",
     *         required=false,
     *
     *         @OA\Schema(type="string", enum={"title", "author", "created_at", "updated_at"}, default="created_at")
     *     ),
     *
     *     @OA\Parameter(
     *         name="order_type",
     *         in="query",
     *         description="Order type (ascending or descending)",
     *         required=false,
     *
     *         @OA\Schema(type="string", enum={"asc", "desc"}, default="desc")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/BookResource")
     *         )
     *     )
     * )
     */
    public function search(IndexRequest $indexRequest): JsonResource
    {
        $books = $this->bookService->search($indexRequest);

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
     *
     * @OA\Get(
     *     path="/api/books/{id}",
     *     summary="Get a specific book by ID",
     *     tags={"Books"},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of the book to retrieve",
     *         required=true,
     *
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *
     *         @OA\JsonContent(ref="#/components/schemas/BookResource")
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="The Book does not found"
     *     )
     * )
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
