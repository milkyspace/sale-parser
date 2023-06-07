<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Parser\ParseController;
use Illuminate\Routing\Controller as BaseController;

class ParseApiController extends BaseController
{

    public function execute(string $parserCode): void
    {
        try {
            $parseController = new ParseController($parserCode);
            $parseController->execute();
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
}
