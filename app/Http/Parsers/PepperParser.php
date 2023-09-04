<?php

namespace App\Http\Parsers;

class PepperParser extends Parser
{

    /**
     * @var string
     */
    protected static string $url = 'https://www.pepper.ru/new';


    /**
     * @param $id
     * @param $link
     *
     * @return void
     */
    private function setRealLink($id, $link): void
    {
        $loop   = \React\EventLoop\Loop::get();
        $client = new \Clue\React\Buzz\Browser($loop);
        $client = $client->withFollowRedirects(false);
        $linkRedirects = "https://www.pepper.ru/visit/threadmain/".str_replace("thread_", "", $id);
        $client->get($linkRedirects)->then(function (\Psr\Http\Message\ResponseInterface $response) use ($id) {
            $query = parse_url($response->getHeader('Location')[0], PHP_URL_QUERY);
            parse_str($query, $params);
            $realUrl = $params['url'];

            if (empty($realUrl)) {
                return;
            }

            $product     = ( new \App\Http\Parsers\Entities\ParserProduct($id) )->setLink($realUrl);
            $productFull = $this->save($product);
            \App\Http\Parsers\Events\OnProductCreate::dispatch($id);
        });
    }


    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return void
     * @throws \DiDom\Exceptions\InvalidSelectorException
     */
    public function responseParse(\Psr\Http\Message\ResponseInterface $response): void
    {
        $document = new \DiDom\Document((string) $response->getBody());
        $products = $document->find('article.thread');

        foreach ($products as $product) {
            $id = $product->attr('id');

            $productInner = ( new \App\Http\Parsers\Entities\ParserProduct($id) );

            if ($this->checkExist($productInner)) {
                continue;
            }

            $titleObj = $product->first('a.js-thread-title');

            $title = '';
            $link = '';
            if($titleObj) {
                $title = $titleObj->text();
                $link = $titleObj->attr('href');
            }

            if ($title === '') {
                continue;
            }


            if ($link === '') {
                continue;
            }

            $imgObj = $product->first('img.thread-image');
            $imgSrc = '';

            if ($imgObj) {
                $imgSrc = $imgObj->attr('src');
            }

            $newPrice    = '';
            $newPriceObj = $product->first('.thread-price');
            if ($newPriceObj) {
                $newPrice = $newPriceObj->text();
            }

            $oldPrice    = '';
            $oldPriceObj = $product->first('.mute--text.text--lineThrough');
            if ($oldPriceObj) {
                $oldPrice = $oldPriceObj->text();
            }

            $desc    = '';
            $descObj = $product->first('div.overflow--wrap-break');
            if ($descObj) {
                $desc = $descObj->text();
            }

            $productInner->setName($title)
                ->setImg($imgSrc)
                ->setOldPrice($oldPrice)
                ->setPrice($newPrice)
                ->setDesc($desc)
                ->setLink($link);

            $fullProduct = $this->save($productInner);

            $this->setRealLink($id, $link);
        }
    }

}
