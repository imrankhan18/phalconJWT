<?php

use Phalcon\Mvc\Controller;


class IndexController extends Controller
{
    public function indexAction()
    {
        
        $this->view->products = Products::find();
        
        // return '<h1>Hello World!</h1>';
        
    }
    public function deleteAction($productid)
    {   
        // die();
        $product= new Products();
        // echo $id;
        $product->productid=$productid;
        $result=$product->delete();
        $this->response->redirect('index');
    }
    public function deleteOrderAction($orderid)
    {   
        // die();
        $order= new Orders();
        // echo $id;
        $order->orderid=$orderid;
        $result=$order->delete();
        $this->response->redirect('index');
    }
}