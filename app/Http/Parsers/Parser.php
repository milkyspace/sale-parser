<?php

namespace App\Http\Parsers;

use App\Http\Parsers\Entities\ParserProduct;
use App\Models\Product;

abstract class Parser implements IParser
{

    /**
     * @var string
     */
    protected static string $url;


    /**
     * @return string
     */
    public function getUrl(): string
    {
        return static::$url;
    }


    public function checkExist(\App\Http\Parsers\Entities\ParserProduct $parserProduct): bool
    {
        $count = \App\Models\Product::where('ext_id', $parserProduct->getExtId())->count();

        return $count > 0;
    }


    /**
     * @param Entities\ParserProduct $parserProduct
     *
     * @return void
     */
    protected function save(\App\Http\Parsers\Entities\ParserProduct $parserProduct
    ): \App\Http\Parsers\Entities\ParserProduct {
        $updateRows = [];

        $class      = new \ReflectionClass(\App\Http\Parsers\Entities\ParserProduct::class);
        foreach ($class->getProperties() as $prop) {
            $propName = $prop->name;
            $method   = 'get' . lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $propName))));

            if ( ! $class->hasMethod($method)) {
                continue;
            }

            if($parserProduct->$method()) {
                $updateRows[$propName] = $parserProduct->$method();
            }
        }

        $prodModel = \App\Models\Product::updateOrCreate(['ext_id' => $parserProduct->getExtId(),], $updateRows);
        $prodInner = new \App\Http\Parsers\Entities\ParserProduct($prodModel->ext_id);

        foreach ($class->getProperties() as $prop) {
            $propName = $prop->name;
            $method   = 'set' . lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $propName))));

            if ( ! $class->hasMethod($method)) {
                continue;
            }

            $prodInner->$method($prodModel->$propName);
        }

        return $prodInner;
    }


    /**
     * @param string $parserId
     *
     * @return ParserProduct
     */
    public static function init(string $parserId): ParserProduct
    {
        $productModel = Product::where('ext_id', $parserId)->first();

        $parserProduct = new ParserProduct($parserId);

        $class      = new \ReflectionClass(\App\Http\Parsers\Entities\ParserProduct::class);
        foreach ($class->getProperties() as $prop) {
            $propName = $prop->name;
            $method   = 'set' . lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $prop->name))));

            if ( ! $class->hasMethod($method)) {
                continue;
            }

            $parserProduct->$method($productModel->$propName);
        }

        return $parserProduct;
    }
}
