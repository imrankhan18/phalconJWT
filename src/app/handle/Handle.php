<?php
namespace App\Handle;

use Phalcon\Di\Injectable;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Events\Event;


class Handle extends Injectable
{
    public function checkzip(Event $event, $product, $setting)
    {

        if ($product->price == null) {
            $product->price = $setting[0]->price;
        }
        if ($product->stock == null) {
            $product->stock = $setting[0]->stock;
        }
        // if ($order->zipcode == null) {
        //     $order->zipcode = $setting[0]->zipcode;
        // }
        if ($setting[0]->title == 'Tag') {
            $product->name = $product->name .''. $product->tags;
        } elseif ($setting[0]->title == 'WithoutTag') {
            $product->name = $product->name;
        }
        return $product;
    }
    public function checkz(Event $event, $order, $setting)
    {

        if ($order->zipcode == null) {
            $order->zipcode = $setting[0]->zipcode;
        }
        return $order;
    }

      
}

