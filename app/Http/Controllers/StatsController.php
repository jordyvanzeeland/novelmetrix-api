<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class StatsController extends Controller
{

    public function getReadingYears(Request $request){
        $years = DB::table('books')
                ->selectRaw('YEAR(readed) as year, COUNT(*) as count')
                ->groupByRaw('YEAR(readed)')
                ->orderBy('year')
                ->get();

        return response()->json($years);
    }

    public function booksPerGenrePerMonth(Request $request){
        $year = $request->header('year');

        if (!$year) {
            return response()->json(['error' => 'No year in header'], 400);
        }

        $books = DB::table('books')
                ->selectRaw('genre, DATE_FORMAT(readed, "%m-%Y") as readed, COUNT(*) as count')
                ->whereYear('readed', $year)
                ->groupBy('genre', 'readed')
                ->orderByDesc('count')
                ->get();

        return response()->json($books, 200);
    }

    public function countGenres(Request $request){
        $year = $request->header('year');

        if (!$year) {
            return response()->json(['error' => 'No year in header'], 400);
        }

        $genres = DB::table('books')
                ->selectRaw('genre, COUNT(*) as count')
                ->whereYear('readed', $year)
                ->groupBy('genre')
                ->orderByDesc('count')
                ->get();

        return response()->json($genres, 200);
    }

    public function countRatings(Request $request){
        $year = $request->header('year');

        if (!$year) {
            return response()->json(['error' => 'No year in header'], 400);
        }

        $ratings = DB::table('books')
                ->selectRaw('rating, COUNT(*) as count')
                ->whereYear('readed', $year)
                ->groupBy('rating')
                ->orderByDesc('rating')
                ->get();

        return response()->json($ratings, 200);
    }

    public function countEnBooks(Request $request){
        $year = $request->header('year');

        if (!$year) {
            return response()->json(['error' => 'No year in header'], 400);
        }

        $enbooks = DB::table('books')
                ->selectRaw('
                    COUNT(*) as count,
                    CASE WHEN COALESCE(en, 0) = 1 THEN "en" ELSE "nl" END as lang,
                    CASE WHEN COALESCE(en, 0) = 1 THEN "English" ELSE "Nederlands" END as name
                ')
                ->whereYear('readed', $year)
                ->groupByRaw('
                    CASE WHEN COALESCE(en, 0) = 1 THEN "en" ELSE "nl" END,
                    CASE WHEN COALESCE(en, 0) = 1 THEN "English" ELSE "Nederlands" END
                ')
                ->orderByDesc('count')
                ->get();

        return response()->json($enbooks, 200);
    }
}