<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Book
 *
 * @OA\Schema(
 *     schema="BookResource",
 *     title="Book Resource",
 *     description="Book resource representation",
 *
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         format="uuid",
 *         description="ID of the book"
 *     ),
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Title of the book"
 *     ),
 *     @OA\Property(
 *         property="author",
 *         type="string",
 *         description="Author of the book"
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Description of the book"
 *     ),
 *     @OA\Property(
 *         property="created_at",
 *         type="string",
 *         format="date-time",
 *         description="Date and time when the book was created"
 *     ),
 *     @OA\Property(
 *         property="updated_at",
 *         type="string",
 *         format="date-time",
 *         description="Date and time when the book was last updated"
 *     ),
 *     @OA\Property(
 *         property="category",
 *         ref="#/components/schemas/CategoryResource",
 *         description="Category of the book"
 *     ),
 *     @OA\Property(
 *         property="provider",
 *         ref="#/components/schemas/ProviderResource",
 *         description="Provider of the book"
 *     )
 * )
 */
class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'book_number' => $this->book_number,
            'slug' => $this->slug,
            'title' => $this->title,
            'author' => $this->author,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'category' => $this->whenLoaded('category', function () {
                return new CategoryResource($this->category);
            }),
            'provider' => $this->whenLoaded('provider', function () {
                return new ProviderResource($this->provider);
            }),
        ];
    }
}
