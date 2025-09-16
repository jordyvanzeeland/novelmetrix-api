<?php

namespace App\Http\Controllers\Write;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Chapter;
use Illuminate\Http\Request;

class ChaptersController extends BaseController
{
    public function insertChapter(Request $request){
        $newChapter = Chapter::create($request->all());

        return response()->json([
            'message' => 'New Chapter added', 
            'newchapter' => $newChapter
        ], 201);
    }

    public function updateChapter(Request $request, int $storyid, int $chapterid){
        $chapter = Chapter::findOrFail($chapterid);
        $chapter->update($request->all());

        return response()->json([
            'message' => 'Chapter updated', 
            'newchapter' => $chapter
        ], 200);
    }

    public function deleteChapter(int $storyid, int $chapterid){
        $chapter = Chapter::findOrFail($chapterid);
        $chapter->delete();

        return response()->json([
            'message' => 'Chapter deleted'
        ], 200);
    }
}