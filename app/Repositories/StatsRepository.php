<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class StatsRepository
{
    public function getBooksPerGenrePerMonth($year)
    {
        return DB::table('books')
            ->selectRaw('genre, DATE_FORMAT(readed, "%m-%Y") as readed, COUNT(*) as count')
            ->whereYear('readed', $year)
            ->groupBy('genre', 'readed')
            ->orderByDesc('count')
            ->get();
    }

    public function getGenreCounts($year)
    {
        return DB::table('books')
            ->selectRaw('genre, COUNT(*) as count')
            ->whereYear('readed', $year)
            ->groupBy('genre')
            ->orderByDesc('count')
            ->get();
    }

    public function getRatingCounts($year)
    {
        return DB::table('books')
            ->selectRaw('rating, COUNT(*) as count')
            ->whereYear('readed', $year)
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
            ->whereYear('readed', $year)
            ->groupByRaw('
                CASE WHEN COALESCE(en, 0) = 1 THEN "en" ELSE "nl" END,
                CASE WHEN COALESCE(en, 0) = 1 THEN "English" ELSE "Nederlands" END
            ')
            ->orderByDesc('count')
            ->get();
    }
}