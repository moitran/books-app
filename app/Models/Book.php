<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JeroenG\Explorer\Application\Explored;
use JeroenG\Explorer\Application\IndexSettings;
use Laravel\Scout\Searchable;

class Book extends Model implements Explored, IndexSettings
{
    use HasFactory;
    use HasUuids;
    use Searchable;
    use Sluggable;

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'book_number',
        'slug',
        'title',
        'description',
        'author',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ],
        ];
    }

    public function indexSettings(): array
    {
        return [
            'analysis' => [
                'analyzer' => [
                    'standard_lowercase' => [
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => ['lowercase'],
                    ],
                ],
            ],
        ];
    }

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'book_number' => 'keyword',
            'slug' => 'text',
            'title' => 'text',
            'author' => 'text',
            'category.name' => 'text',
            'category.slug' => 'text',
            'created_at' => 'date',
            'updated_at' => 'date',
        ];
    }

    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'book_number' => $this->book_number,
            'slug' => $this->slug,
            'title' => $this->title,
            'author' => $this->author,
            'category.name' => $this->category->name,
            'category.slug' => $this->category->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
