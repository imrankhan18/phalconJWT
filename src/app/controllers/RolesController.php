<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;

class RolesController extends Controller{

    public function indexAction()
    {
        
    }
    public function rolesAction()
    {
        $roles= new Roles();
        $roles->assign(
            $this->request->getPost(),
            ['roles']
        );
        $roles->save();
        $this->response->redirect('roles');
        
        
    }
}