<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\Provider
 *
 * @OA\Schema(
 *     schema="ProviderResource",
 *     title="Provider Resource",
 *     description="Provider resource representation",
 *
 *     @OA\Property(
 *         property="id",
 *         type="string",
 *         format="uuid",
 *         description="ID of the provider"
 *     ),
 *     @OA\Property(
 *         property="name",
 *         type="string",
 *         description="Name of the provider"
 *     ),
 *     @OA\Property(
 *         property="slug",
 *         type="string",
 *         description="Slug of the provider"
 *     )
 * )
 */
class ProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
