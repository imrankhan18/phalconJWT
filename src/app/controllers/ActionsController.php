<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;

class ActionsController extends Controller
{
    public function indexAction()
    {
        $actions= new Actions();
        $actions->assign(
            $this->request->getPost(),
            ['actions']
        );
        $actions->save();
        
    }
}