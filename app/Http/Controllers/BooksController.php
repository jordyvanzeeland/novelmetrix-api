<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    /**
     * Require authentication for all methods in this controller.
     */

    public function __construct(){
        $this->middleware('auth:api');
    }

    /**
     * Retrieve all books of userid including filtering.
     */

     public function getUserBooks(Request $request){
        $user = Auth::user();

        $query = Book::where('userid', $user->id);

        if($request->header('author')){
            $query->where('author', $request->header('author'));
        }

        if($request->header('genre')){
            $query->where('genre', $request->header('genre'));
        }

        if($request->header('year')){
            $query->whereYear('readed', $request->header('year'));
        }

        if($request->header('rating')){
            $query->where('rating', $request->header('rating'));
        }

        if($request->header('language')){
            $query->where('en', $request->header('language'));
        }

        $books = $query->get();
        return response()->json($books, 200);
    }

    /**
    * Retrieve current reading book of user.
    */

    public function getCurrentReadingBookOfUser(){
        $user = Auth::user();
        $books = Book::whereNull('readed')
                 ->where('userid', $user->id)->get();

        return response()->json($books, 200);
    }

    /**
    * Retrieve a single book by it's id.
    * If the book is not found, then it wil give a 404 response
    */

    public function getBook(int $bookid){
        $book = Book::find($bookid);

        if(!$bookid){
            return response()->json([
                'message' => 'Book not found'
            ], 404);
        }

        return response()->json($book, 200);
    }

    /**
    * Insert a new book
    */

    public function insertBook(Request $request){
        $user = Auth::user();
        $request["userid"] = $user->id;

        $newBook = Book::create($request->all());

        return response()->json([
            'message' => 'New book added', 
            'newbook' => $newBook
        ], 201);
    }

    /**
    * Update an existing book by it's id.
    * If the book is not found, then it will give a 404 response
    */

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

    /**
    * Delete an existing book by it's id.
    * If the book is not found, then it wil give a 404 response
    */

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