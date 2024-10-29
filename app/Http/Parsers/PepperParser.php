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
        $loop = \React\EventLoop\Loop::get();
        $client = new \Clue\React\Buzz\Browser($loop);
        $client = $client->withFollowRedirects(false);
        $linkRedirects = $link;
        $client->get($linkRedirects)->then(function (\Psr\Http\Message\ResponseInterface $response) use ($id) {
            $query = parse_url($response->getHeader('Location')[0], PHP_URL_QUERY);
            parse_str($query, $params);
            $realUrl = $params['url'];

            if (empty($realUrl)) {
                return;
            }

            $product = (new \App\Http\Parsers\Entities\ParserProduct($id))->setLink($realUrl);
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
        $document = new \DiDom\Document((string)$response->getBody());
        $products = $document->find('article');

        foreach ($products as $product) {
            $id = $product->attr('data-permalink');
            $id = md5($id);

            $productInner = (new \App\Http\Parsers\Entities\ParserProduct($id));

            if ($this->checkExist($productInner)) {
                continue;
            }

            $titleBlock = $product->first('.custom-card-title');
            $titleObj = $titleBlock->first('a');
            $linkObj = $product->first('a.w-full.h-full.flex.justify-center.items-center.gtm_buy_now_homepage');

            $title = '';
            $link = '';
            if ($titleObj) {
                $title = $titleObj->text();
            }
            if ($linkObj) {
                $link = $linkObj->attr('href');
            }

            if ($title === '') {
                continue;
            }

            if ($link === '') {
                continue;
            }

            $imgObj = $product->first('img');
            $imgSrc = '';
            if ($imgObj) {
                $imgSrc = $imgObj->attr('src');
            }

            $priceBlock = $product->first('.flex.items-center.relative.whitespace-nowrap.overflow-hidden');
            $newPrice = '';
            $newPriceObj = $priceBlock->first('.text-lg.font-bold.text-primary.mr-2');
            if ($newPriceObj) {
                $newPrice = trim($newPriceObj->text());
            }

            $oldPrice = '';
            $oldPriceObj = $priceBlock->first('.text-lg.line-through.text-secondary-text-light.mr-2');
            if ($oldPriceObj) {
                $oldPrice = trim($oldPriceObj->text());
            }

            $descBlock = $product->first('.row-start-3.col-start-1.col-end-5.text-secondary-text-light.h-auto.flex.items-center.break-long-word');
            $desc = '';
            $descObj = $descBlock->first('span');
            if ($descObj) {
                $desc = trim($descObj->text());
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
