<?php

namespace App\Console\Commands;

use App\Jobs\SendPostEmailJob;
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
        $websites = Website::with('subscribers')->get();
        foreach($websites as $website){
            foreach($website->subscribers as $subscriber){
                $this->sendUnsentPosts($website->id,$subscriber->id);
            }
        }

    }

    private function sendUnsentPosts($website_id, $user_id)
    {
        $webistePosts = Website::where('id', $website_id)->with('posts')->first();
        foreach ($webistePosts->posts as $post) {
            if(! $this->checkSentBefore($user_id,$post->id)){
                SendPostEmailJob::dispatch($user_id, $post, $website_id);
            }
        }
    }

    private function checkSentBefore($user_id,$post_id)
    {
        $isExists = DB::table('sent_posts')->where('user_id',$user_id)->where('post_id',$post_id)->exists();
        return $isExists;
    }
}
