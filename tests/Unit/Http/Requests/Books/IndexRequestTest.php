<?php

namespace Tests\Unit\Http\Requests\Books;

use App\Http\Requests\Books\IndexRequest;
use Tests\TestCase;

class IndexRequestTest extends TestCase
{
    public function test_rules_set_are_correct()
    {
        $indexRequest = new IndexRequest();
        $this->assertEquals([
            'query' => 'nullable|string|max:255',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'order_by' => 'nullable|string|in:title,author,created_at,updated_at',
            'order_type' => 'nullable|string|in:asc,desc',
            'category_id' => 'nullable|exists:categories,id',
            'provider_id' => 'nullable|exists:providers,id',
        ], $indexRequest->rules());
    }

    public function test_authorize_is_true()
    {
        $indexRequest = new IndexRequest();
        $this->assertTrue($indexRequest->authorize());
    }
}
