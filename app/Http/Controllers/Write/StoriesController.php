<?php

namespace App\Http\Controllers\Write;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Models\Story;
use Illuminate\Http\Request;

class StoriesController extends BaseController
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function getStories(){
        $stories = Story::get();
        return response()->json($stories, 200);
    }

    public function getStoryByID(int $storyid){
        $story = Story::find($storyid);

        if(!$story){
            return response()->json([
                'message' => 'Story not found'
            ], 404);
        }

        return response()->json($story, 200);
    }

    public function insertStory(Request $request){
        $newStory = Story::create($request->all());

        return response()->json([
            'message' => 'New Story added', 
            'newbook' => $newStory
        ], 201);
    }

    public function updateStory(Request $request, int $storyid){
        // Todo: When chapter/revision changes, the update timestamp has also to be updated

        $story = Story::find($storyid);

        if(!$story){
            return response()->json([
                'message' => 'Story not found'
            ], 404);
        }

        $story->update($request->all());

        return response()->json([
            'message' => 'Story updated', 
            'newbook' => $story
        ], 200);
    }

    public function deleteStory(int $storyid){
        // Todo: Check for chapters and revisions to delete from story

        $story = Story::find($storyid);

        if(!$story){
            return response()->json([
                'message' => 'Story not found'
            ], 404);
        }

        $story->delete();

        return response()->json([
            'message' => 'Story deleted'
        ], 200);
    }
}