<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ProductSend implements ShouldQueue
{

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ?\App\Http\Parsers\Entities\ParserProduct $product = null;


    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(\App\Http\Parsers\Entities\ParserProduct $product)
    {
        $this->product = $product;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $this->product
        // Telegram
    }
}
