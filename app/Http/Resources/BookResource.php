<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Book
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
