<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $chunks = env('BOOK_DUMMY_DATA_TOTAL_RECORDS', 1);

        for ($i = 0; $i < $chunks; $i++) {
            $books = Book::factory()->count(1000)->make()->toArray();
            DB::table('books')->insert($books);
        }
    }
}
