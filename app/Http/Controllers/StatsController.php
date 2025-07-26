<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Repositories\StatsRepository;
use Illuminate\Http\Request;
use DB;

class StatsController extends Controller
{
    protected $statsRepo;

    public function __construct(StatsRepository $statsRepo){
        $this->statsRepo = $statsRepo;
    }

    public function getDashStats(Request $request){
        $year = $request->header('year');

        $books = $this->statsRepo->getBooksPerGenrePerMonth($year);
        $genres = $this->statsRepo->getGenreCounts($year);
        $ratings = $this->statsRepo->getRatingCounts($year);
        $languages = $this->statsRepo->getLanguageCounts($year);

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