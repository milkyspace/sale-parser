<?php

namespace App\Http\Parsers\Events\Listeners;

use DefStudio\Telegraph\Keyboard\Button;
use DefStudio\Telegraph\Keyboard\Keyboard;

class SendProduct
{

    /**
     * Handle the event.
     *
     * @param \App\Http\Parsers\Events\OnProductCreate $event
     *
     * @return void
     */
    public function handle(\App\Http\Parsers\Events\OnProductCreate $event)
    {
        // \App\Jobs\ProductSend::dispatch($event->product); Queue

        $productId = $event->productId;
        $product = \App\Http\Parsers\Parser::init($productId);
        $bot = \DefStudio\Telegraph\Models\TelegraphBot::where('name', env('TELEGRAM_BOT_NAME'))->first();
        $chat = $bot->chats()->first();
        $html = "
<b>{$product->getName()}</b>";

        if ($product->getPrice()) {
            $html .= "

Цена: <b>{$product->getPrice()}</b>";
        }

        if ($product->getOldPrice()) {
            $html .= "
Старая цена: {$product->getOldPrice()}";
        }

        if ($product->getDesc()) {
            $html .= "

{$product->getDesc()}
";
        }

        $productFromBd = \Illuminate\Support\Facades\DB::table('products')
            ->where('ext_id', '=', $product->getExtId())
            ->where('posted', '=', true)
            ->get();

        if ($productFromBd->count() > 0) {
            return;
        }

        /** @var \DefStudio\Telegraph\Models\TelegraphChat $chat */
        $send = $chat
            ->html($html)
            ->photo($product->getImg())
            ->keyboard(Keyboard::make()->buttons([
                Button::make('Перейти к скидке')->url($product->getLink()),
            ]))->send();

        \App\Models\Product::updateOrCreate([
            "ext_id" => $product->getExtId(),
        ], [
            "posted" => true,
        ]);
    }
}
