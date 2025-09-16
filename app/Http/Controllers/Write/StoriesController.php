<?php

namespace App\Http\Controllers\Write;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Story;
use App\Models\Chapter;
use App\Repositories\StoryRepository;
use Illuminate\Http\Request;

class StoriesController extends BaseController
{
    private $StoryRepository;

    public function __construct(StoryRepository $storyRepository){
        $this->middleware('auth:api');
        $this->StoryRepository = $storyRepository;
    }

    public function getStories(){
        $stories = Story::get();
        return response()->json($stories, 200);
    }

    public function getStoryByID(int $storyid){
        $story = Story::findOrFail($storyid);
        $chapterid = request()->input('chapterid');
        $chapters = $this->StoryRepository->findChaptersOfStory($storyid, $chapterid);

        return response()->json([
            'story' => $story,
            'chapters' => $chapters
        ], 200);
    }

    public function insertStory(Request $request){
        $newStory = Story::create($request->all());

        return response()->json([
            'message' => 'New Story added', 
            'newbook' => $newStory
        ], 201);
    }

    public function updateStory(Request $request, int $storyid){
        $story = Story::findOrFail($storyid);
        $story->update($request->all());

        return response()->json([
            'message' => 'Story updated', 
            'newbook' => $story
        ], 200);
    }

    public function deleteStory(int $storyid){
        $story = Story::findOrFail($storyid);
        $chapters = $this->StoryRepository->findChaptersOfStory($storyid);

        foreach($chapters as $chapter){
            $chapter->delete();
        }

        $story->delete();

        return response()->json([
            'message' => 'Story deleted'
        ], 200);
    }
}