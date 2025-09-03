<?php

namespace App\Repositories;

use App\Models\Chapter;

class ChapterRepository {

    public function findChapterById(int $chapterid) {
        $chapter = Chapter::find($chapterid);

        if(!$chapter){
            return response()->json([
                'message' => 'Chapter not found'
            ], 404);
        }

        return $chapter;
    }
}