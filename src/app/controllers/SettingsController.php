<?php

use Phalcon\Mvc\Controller;

class SettingsController extends Controller
{
    public function indexAction()
    {
    }
    public function addAction()
    {
        $tag=$this->request->getPost('title');
        $price=$this->request->getPost('price');
        $stock=$this->request->getPost('stock'); 
        $zipcode=$this->request->getPost('zipcode');
        $value=Settings::find('id=1');
        $value[0]->title=$tag;
        $value[0]->price=$price;
        $value[0]->stock=$stock;
        $value[0]->zipcode=$zipcode;
        $value[0]->update();
        // print_r($value[0]->price);
        // die();
        $this->response->redirect('settings');
    }

    public function defaultAction()
    {
        // print_r($this);
        
    }
   



}