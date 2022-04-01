<?php

use Phalcon\Mvc\Model;

class Orders extends Model
{
    public $orderid;
    public $name;
    public $address;
    public $zipcode;
    public $dropdown;
    public $quantity;
}