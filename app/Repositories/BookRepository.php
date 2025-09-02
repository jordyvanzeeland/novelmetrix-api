<?php

namespace App\Repositories;

use App\Models\Story;

class BookRepository {

    public function findBookById(int $bookid) {
        $book = Book::find($bookid);

        if(!$book){
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        return $book;
    }
}