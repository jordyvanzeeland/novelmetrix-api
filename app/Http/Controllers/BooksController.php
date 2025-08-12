<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function getUserBooks(Request $request)
    {
        $user = Auth::user();

        $filters = ['author', 'genre', 'year', 'rating', 'language'];

        $query = Book::where('userid', $user->id);

        foreach ($filters as $filter) {
            $value = $request->header($filter);
            if ($value) {
                if ($filter === 'year') {
                    $query->whereYear('readed', $value);
                } else {
                    $query->where($filter, $value);
                }
            }
        }

        $books = $query->get();

        return response()->json($books, 200);
    }

    public function getCurrentReadingBookOfUser(){
        $user = Auth::user();
        $books = Book::whereNull('readed')
                 ->where('userid', $user->id)->get();

        return response()->json($books, 200);
    }

    public function getBook(int $bookid){
        $book = Book::find($bookid);

        if(!$bookid){
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json($book, 200);
    }

    public function insertBook(Request $request){
        $user = Auth::user();
        $request["userid"] = $user->id;

        $newBook = Book::create($request->all());

        return response()->json([
            'message' => 'New book added', 
            'newbook' => $newBook
        ], 201);
    }

    public function updateBook(Request $request, int $bookid){
        $book = Book::find($bookid);

        if(!$book){
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        $user = Auth::user();
        $request["userid"] = $user->id;
        
        $book->update($request->all());

        return response()->json([
            'message' => 'Book updated', 
            'book' => $book
        ], 200);
    }

    public function deleteBook(int $bookid){
        $book = Saldo::find($bookid);
        
        if(!$book){
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        $book->delete();
        
        return response()->json([
            'message' => 'Book deleted'
        ], 200);
    }
}