<?php

namespace App\Console\Commands;

use App\Jobs\SendPostEmailJob;
use App\Models\Post;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendEmailsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscribers:send-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails to the subscribers ';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $websites = Website::select('websites.id')->get();

        foreach($websites as $website){
            
            $website->load(['subscribers'=> function($query) use ($website){
                $query->select('users.id')->chunk(50, function($subscribers) use($website) {
                    foreach($subscribers as $subscriber){
                        $this->sendUnsentPosts($website->id, $subscriber->id);
                    }
                });
            }]);
        }

    }

    private function sendUnsentPosts($website_id, $user_id)
    {
        $webistePosts = Post::where('website_id', $website_id)
                        ->whereNotExists(function($query) use($user_id){
                            $query->select(DB::raw(1))
                                ->from('sent_posts')
                                ->where('sent_posts.user_id', $user_id)
                                ->whereRaw('sent_posts.post_id = posts.id');
                        })
                        ->get();

        foreach ($webistePosts as $post) {
            SendPostEmailJob::dispatch($website_id, $user_id, $post);
        }

    }

    // private function checkSentBefore($website_id, $user_id, $post_id)
    // {
    //     $isExists = DB::table('sent_posts')
    //                 ->where('user_id', $user_id)
    //                 ->where('post_id', $post_id)
    //                 ->where('website_id', $website_id)
    //                 ->exists();
    //     return $isExists;
    // }
}
