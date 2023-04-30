<?php

namespace App\Http\Controllers;

use App\Events\PostCreatedEvent;
use App\Http\Requests\NewPostRequest;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(NewPostRequest $request)
    {
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'website_id' => $request->website_id,
        ]);

        if($post){
            event(new PostCreatedEvent($post));

            return response()->json([
                'status'         => 'success',
                'message'        => 'Post has been created successfully',
                'post'           => $post
            ],201);
        }

        return response()->json([
            'status'         => 'error',
            'message'        => 'Post has not been created',
        ],500);
    }
    
}
