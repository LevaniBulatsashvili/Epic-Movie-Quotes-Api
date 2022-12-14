<?php

namespace App\Events;

use App\Models\QuoteComment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserQuoteUpdated implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $quote;
    public $like;
    public $comment;
    protected $channel_user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($quote, $action, $id)
    {
        $this->quote = $quote;

        $this->channel_user = implode("-", explode(" ", $quote->username));
        if ($action === 'comment') {
            $this->comment = QuoteComment::findOrFail($id)->only(['username', 'thumbnail']);
        } elseif ($action === 'like') {
            $this->like = User::findOrFail($id)->only(['username', 'thumbnail']);
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('quotes.'.$this->channel_user);
    }
}
