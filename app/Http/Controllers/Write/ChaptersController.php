<?php

namespace App\Http\Controllers\Write;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Chapter;
use App\Repositories\ChapterRepository;
use Illuminate\Http\Request;

class ChaptersController extends BaseController
{
    private $ChapterRepository;

    public function __construct(ChapterRepository $chapterRepository){
        $this->middleware('auth:api');
        $this->ChapterRepository = $chapterRepository;
    }

    public function insertChapter(Request $request){
        $newChapter = Chapter::create($request->all());

        return response()->json([
            'message' => 'New Chapter added', 
            'newbook' => $newChapter
        ], 201);
    }

    public function updateChapter(Request $request, int $chapterid){
        $chapter = $this->ChapterRepository->findChapterById($chapterid);
        $chapter->update($request->all());

        return response()->json([
            'message' => 'Chapter updated', 
            'newbook' => $chapter
        ], 200);
    }

    public function deleteChapter(int $chapterid){
        $chapter = $this->ChapterRepository->findChapterById($chapterid);
        $chapter->delete();

        return response()->json([
            'message' => 'Chapter deleted'
        ], 200);
    }
}