<?php

use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Events\Manager as EventsManager;
class OrderController extends Controller
{
    public function indexAction()
    {
        
    }
    public function registerAction()
    {
        $order = new Orders();
        $order->assign(
            $this->request->getPost(),
            ['name', 'address' , 'zipcode','dropdown','quantity']
        );
      //   $order->save();
        $values = Settings::find('id = 1');
        $eventsManager = $this->di->get('EventsManager');
        $val = $eventsManager->fire('Handle:checkz', $order, $values);
      //   print_r($val);
      //   die;
        $success = $val->save();
         // $values = Settings::find('id = 1');
         // $eventsManager = $this->di->get('EventsManager');
         // $val = $eventsManager->fire('Handle:checkzip', $this, $values);
         // $order->assign(
         //    $val,[ 'name', 'address' , 'zipcode','dropdown','quantity']
         // );
         // $order->save();

        
   
      }
}