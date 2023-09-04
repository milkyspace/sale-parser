<?php

namespace App\Http\Parsers;

interface IParser
{
    public function responseParse(\Psr\Http\Message\ResponseInterface $response);
}
