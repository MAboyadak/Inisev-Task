<?php

namespace App\Http\Controllers;

use App\Events\PostCreatedEvent;
use App\Http\Requests\NewPostRequest;
use App\Interfaces\PostRepositoryInterface;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;

    public function __construct(PostRepositoryInterface $postRepo)
    {
        $this->postRepository = $postRepo;
    }

    public function store(NewPostRequest $request)
    {
        $post = $this->postRepository->store($request);

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
