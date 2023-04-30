<?php

namespace App\Listeners;

use App\Events\PostCreatedEvent;
use App\Jobs\SendPostEmailJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNewPostEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostCreatedEvent $event): void
    {
        $post = $event->post;
        $website = $post->website;
        $subscribers = $website->subscribers;

        foreach ($subscribers as $subscriber) {
            SendPostEmailJob::dispatch($subscriber->id, $post, $website->id);
        }
    }
}
