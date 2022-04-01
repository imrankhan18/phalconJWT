<?php

use Phalcon\Mvc\Controller;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;

class ProductController extends Controller{

    public function indexAction()
    {

    }

    public function registerAction()
    {
       
        // print_r($settings['id']);
        // die();
        // $escaper = new \App\Components\MyEscaper();
        // $this->escaper->sanitize();
    //     $data=array(
    //     "name"=>$escaper->sanitize($this->request->getPost('name')),
    //     "description"=>$escaper->sanitize($this->request->getPost('description')),
    //     "tags"=>$escaper->sanitize($this->request->getPost('tags')),
    //     "price"=>$escaper->sanitize($this->request->getPost('price')),
    //     "stock"=>$escaper->sanitize($this->request->getPost('stock'))
    // ); 
                $products = new Products();
                $products->assign(
                    $this->request->getPost(),
                    [ 'name', 'description' , 'tags','price','stock']
                );
                $values = Settings::find('id = 1');
                $eventsManager = $this->di->get('EventsManager');
                $val = $eventsManager->fire('Handle:checkzip', $products, $values);
                $success=$val->save();
              

                $this->view->success = $success;

        if ($success) {
                    $this->view->message = "Added succesfully";
        } else {
            $this->view->message = "Not Add succesfully due to following reason: <br>".implode("<br>", $products->getMessages());
            $message = implode(" & ", $products->getMessages());
            $adapter = new Stream('../app/logs/login.log');
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