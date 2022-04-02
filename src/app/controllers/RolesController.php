<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;

class RolesController extends Controller{

    public function indexAction()
    {
        
    }
    public function registerAction()
    {
        $roles= new Roles();
        $roles->assign(
            $this->request->getPost(),
            ['roles']
        );
        $success=$roles->save();
        // $this->response->redirect('roles');
        if ($success) {
            $this->view->message = "Added succesfully";
        } else {
            $this->view->message = "Not Added succesfully due to following reason: <br>".implode("<br>", $roles->getMessages());
            $message = implode(" & ", $roles->getMessages());
            $adapter = new Stream('../app/logs/roles.log');
            $logger = new Logger(
                'messages',
                [
                    'main'=>$adapter,
                ]
            );
                $logger->error($message);
        }
        
        
    }
}