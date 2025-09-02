<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Book;
use App\Repositories\BookRepository;
use Illuminate\Http\Request;

class BooksController extends Controller
{
    private $BookRepository;

    public function __construct(BookRepository $bookRepository){
        $this->middleware('auth:api');
        $this->BookRepository = $bookRepository;
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
        $book = $this->BookRepository->findBookById($bookid);
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
        $book = $this->BookRepository->findBookById($bookid);

        $user = Auth::user();
        $request["userid"] = $user->id;
        
        $book->update($request->all());

        return response()->json([
            'message' => 'Book updated', 
            'book' => $book
        ], 200);
    }

    public function deleteBook(int $bookid){
        $book = $this->BookRepository->findBookById($bookid);
        $book->delete();
        
        return response()->json([
            'message' => 'Book deleted'
        ], 200);
    }
}