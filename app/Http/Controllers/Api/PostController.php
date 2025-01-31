<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    function createPost(Request $request) {
        try {
            $posts = [];

            for($i = 40001; $i <=50000; $i++){
                $posts[] = [
                    'title' => 'Post '.$i,
                    'content' => 'This is a dummy Post '.$i,
                    'created_at' => date('Y-m-d h:i:s')
                ];
            }
            //$store  = Post::insert($posts);
            return response()->json(['message' => "Post created successfully.", 'status' => 200]);
        } catch (\Exception $e) {
            throw new $e; 
        }
        
    }


    function getPosts(Request $reqeust)  {
        try {
            $filters = $reqeust->all();
            $limit = $filters['limit']??null;
            $cacheKey = 'posts_' . md5(json_encode($filters));
            // $lock = Cache::lock('lock:' . $cacheKey, 10);
            if(Cache::has($cacheKey)){
                $posts = Cache::get($cacheKey); // Get data from cache
                Log::info('Data fetched from cache');
                
            } else {
                // Check and store posts in the cache for 5 minutes
                $posts = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters) {
                    if(isset($filters['limit']) && $filters['limit'] != null){
                        return Post::limit($filters['limit'])->get()->toArray();
                    } else {
                        return Post::get()->toArray(); // Fetch posts from the database
                    }
                    
                });
                Log::info('Data fetched from database');
            }
            

            return response()->json(['message' => "Post fetched successfully.", 'status' => 200, 'data' => $posts]);

            // $posts = Cache::remember($cacheKey, 300, function () {
            //     return Post::get();
            // });
        
            // // Add cache-control headers for CloudFront
            // return response()->json($posts, Response::HTTP_OK)
            //     ->header('Cache-Control', 'max-age=300, public')
            //     ->header('Expires', now()->addMinutes(5)->toRfc7231String());

        } catch (\Exception $e) {
            dd($e);
            return response()->json(['message' => "Something went wrong.", 'status' => 500, 'desc' => $e->getMessage()]);
        }
    }
}
