<?php

namespace App\Events\Payment;

use App\Models\Loan;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class PaidLoan
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $loanId;

    /**
     * PaidLoan constructor.
     * @param $loanId
     */
    public function __construct($loanId)
    {
        $this->loanId = $loanId;
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
