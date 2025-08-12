<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class StatsController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function getBooksPerGenrePerMonth($year)
    {
        return DB::table('books')
            ->selectRaw('genre, readed, COUNT(*) as count')
            ->whereBetween('readed', ["{$year}-01-01", "{$year}-12-01"])
            ->groupBy('genre', 'readed')
            ->orderByDesc('count')
            ->get();
    }

    public function getGenreCounts($year)
    {
        return DB::table('books')
            ->selectRaw('genre, COUNT(*) as count')
            ->whereBetween('readed', ["{$year}-01-01", "{$year}-12-01"])
            ->groupBy('genre')
            ->orderByDesc('count')
            ->get();
    }

    public function getRatingCounts($year)
    {
        return DB::table('books')
            ->selectRaw('rating, COUNT(*) as count')
            ->whereBetween('readed', ["{$year}-01-01", "{$year}-12-01"])
            ->groupBy('rating')
            ->orderByDesc('rating')
            ->get();
    }

    public function getLanguageCounts($year)
    {
        return DB::table('books')
            ->selectRaw('
                COUNT(*) as count,
                CASE WHEN COALESCE(en, 0) = 1 THEN "en" ELSE "nl" END as lang,
                CASE WHEN COALESCE(en, 0) = 1 THEN "English" ELSE "Nederlands" END as name
            ')
            ->whereBetween('readed', ["{$year}-01-01", "{$year}-12-01"])
            ->groupByRaw('
                CASE WHEN COALESCE(en, 0) = 1 THEN "en" ELSE "nl" END,
                CASE WHEN COALESCE(en, 0) = 1 THEN "English" ELSE "Nederlands" END
            ')
            ->orderByDesc('count')
            ->get();
    }

    public function getDashStats(Request $request){
        $year = $request->header('year');

        $books = $this->getBooksPerGenrePerMonth($year);
        $genres = $this->getGenreCounts($year);
        $ratings = $this->getRatingCounts($year);
        $languages = $this->getLanguageCounts($year);

        return response()->json([
            'books' => $books,
            'genres' => $genres,
            'ratings' => $ratings,
            'languages' => $languages
        ], 200);
    }

    public function getReadingYears(Request $request){
        $years = DB::table('books')
                ->selectRaw('YEAR(readed) as year, COUNT(*) as count')
                ->groupByRaw('YEAR(readed)')
                ->orderBy('year')
                ->get();

        return response()->json($years);
    }
}