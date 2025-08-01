<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptServiceWorkshopProposalEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $workshopServiceProposal;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($workshopServiceProposal)
    {
        $this->workshopServiceProposal = $workshopServiceProposal;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
