<?php

namespace App\Http\Controllers\Parser;


class ParseController
{

    private \React\EventLoop\LoopInterface $loop;

    private \Clue\React\Buzz\Browser $client;

    private string $parserCode;

    private \App\Http\Parsers\IParser $parser;


    public function __construct($parserCode)
    {
        $this->loop       = \React\EventLoop\Loop::get();
        $this->client     = new \Clue\React\Buzz\Browser($this->loop);
        $this->parserCode = $parserCode;
    }


    /**
     * @throws \Exception
     */
    private function createParserInstance(): void
    {
        $class = '\\App\\Http\\Parsers\\' . ucfirst($this->parserCode) . 'Parser';
        if ( ! class_exists($class)) {
            throw new \Exception('Parser is not defined');
        }

        $parser = new $class;
        if ( ! $parser instanceof \App\Http\Parsers\IParser) {
            throw new \Exception('Parser is not defined');
        }

        $this->parser = $parser;
    }


    private function responseParse(\Psr\Http\Message\ResponseInterface $response): void
    {
        $this->parser->responseParse($response);
    }


    /**
     * @throws \Exception
     */
    public function execute(): void
    {
        $this->createParserInstance();

        $this->client->get($this->parser->getUrl())->then(function (
            \Psr\Http\Message\ResponseInterface $response
        ) {
            $this->responseParse($response);
        });

        $this->loop->run();
    }
}
