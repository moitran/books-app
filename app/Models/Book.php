<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use JeroenG\Explorer\Application\Explored;
use JeroenG\Explorer\Application\IndexSettings;
use JeroenG\Explorer\Domain\Analysis\Analysis;
use JeroenG\Explorer\Domain\Analysis\Analyzer\StandardAnalyzer;
use JeroenG\Explorer\Domain\Analysis\Filter\SynonymFilter;
use Laravel\Scout\Searchable;

/**
 * @property string $id
 * @property string $book_number
 * @property string $slug
 * @property string $title
 * @property string $author
 * @property string|null $description
 * @property string $provider_id
 * @property string $category_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Category $category
 * @property-read Provider $provider
 *
 * @method static \Database\Factories\BookFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Book findSimilarSlugs(string $attribute, array $config, string $slug)
 * @method static \Illuminate\Database\Eloquent\Builder|Book newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Book query()
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereAuthor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereBookNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereProviderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Book withUniqueSlugConstraints(\Illuminate\Database\Eloquent\Model $model, string $attribute, array $config, string $slug)
 *
 * @mixin \Eloquent
 */
class Book extends Model implements Explored, IndexSettings
{
    use HasFactory;
    use HasUuids;
    use Searchable;
    use Sluggable;

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = true;

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
        $synonymFilter = new SynonymFilter;

        $synonymAnalyzer = new StandardAnalyzer('synonym');
        $synonymAnalyzer->setFilters(['lowercase', $synonymFilter]);

        return (new Analysis)
            ->addAnalyzer($synonymAnalyzer)
            ->addFilter($synonymFilter)
            ->build();
    }

    public function mappableAs(): array
    {
        return [
            'id' => 'keyword',
            'book_number' => 'keyword',
            'slug' => 'keyword',
            'title' => [
                'type' => 'text',
                'analyzer' => 'synonym',
            ],
            'author' => 'text',
            'created_at' => 'date',
            'updated_at' => 'date',
            'category' => [
                'id' => 'keyword',
                'name' => 'text',
            ],
            'provider' => [
                'id' => 'keyword',
                'name' => 'text',
            ],
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
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // Category data
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->name,
            ],
            // Provider data
            'provider' => [
                'id' => $this->provider->id,
                'name' => $this->provider->name,
            ],
        ];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Category, Book>
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Provider, Book>
     */
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with(['category', 'provider']);
    }
}
