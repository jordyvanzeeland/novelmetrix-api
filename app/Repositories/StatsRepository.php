<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class StatsRepository
{
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
}