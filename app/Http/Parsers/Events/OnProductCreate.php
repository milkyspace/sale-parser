<?php

namespace App\Http\Parsers\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OnProductCreate
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $productId;

    public function __construct(string $productId)
    {
        $this->productId = $productId;
    }
}
