<?php

use Phalcon\Mvc\Controller;


class Index2Controller extends Controller
{
    public function indexAction()
    {
        
        $this->view->order = Orders::find();
        
        // return '<h1>Hello World!</h1>';
        
    }
}
