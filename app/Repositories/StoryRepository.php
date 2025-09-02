<?php

namespace App\Repositories;

use App\Models\Story;
use App\Models\Chapter;

class StoryRepository {

    public function findStoryById(int $storyid) {
        $story = Story::find($storyid);

        if(!$story){
            return response()->json([
                'message' => 'Story not found'
            ], 404);
        }

        return $story;
    }

    public function findChaptersOfStory(int $storyid, int $chapterid = null){
        $chaptersQuery = Chapter::where('storyid', $storyid);

        if($chapterid){
            $chapters = $chaptersQuery->where('id', $chapterid)->first();
        }else{
            $chapters = $chaptersQuery->get();
        }

        return $chapters;
    }

}