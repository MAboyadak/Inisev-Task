<?php

namespace App\Jobs;

use App\Mail\PostEmail;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendPostEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;
    public function retryAfter()
    {
        return 10;
    }

    /**
     * Create a new job instance.
     */
    public function __construct(private $user_id, private $post, private $website_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $postSent = DB::table('sent_posts')->where('user_id',$this->user_id)->where('post_id',$this->post->id)->exists();
        if(!$postSent){
            DB::table('sent_posts')->insert([
                'user_id'    => $this->user_id,
                'post_id'    => $this->post->id,
                'website_id' => $this->website_id,
            ]);
        }

        $user = User::find($this->user_id);
        Mail::to($user)->send(new PostEmail($this->post));
        
    }
}
