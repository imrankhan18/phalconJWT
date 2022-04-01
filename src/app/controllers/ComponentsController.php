<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;

class ComponentsController extends Controller{

    public function indexAction()
    {

    }
    public function compoAction()
    {
        $components= new Components();
        $components->assign(
            $this->request->getPost(),
            ['components']
        );
        $components->save();
        $this->response->redirect('components');
    }
}