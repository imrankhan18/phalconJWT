<?php


namespace App\Console;

require_once'../vendor/autoload.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Cli\Task;
use Settings;
use Products;
use Orders;

class TokenTask extends Task
{
    public function getTokenAction($role = 'manager')
    {
        echo $role. PHP_EOL;

    }
    public function deletelogAction()
    {
        $logs = scandir(APP_PATH."/logs/", 1);
        foreach($logs as $val)
        {
            if($val!='..' && $val!='.')
            {
                unlink(APP_PATH."/logs/$val");
            }
        }
        echo "All File Deleted";
        die();

    }
    public function createTokenAction()
    {
        if ($claims['sub'] ='admin') { 
                $key = "example_key";
                $payload = array(
                    "iss" => "http://example.org",
                    "aud" => "http://example.com",
                    "iat" => 1356999524,
                    "nbf" => 1357000000,
                    "sub" => 'manager',
                );
            
            
                $jwt = JWT::encode($payload, $key, 'HS256');
           
                 echo $jwt;
                 die();
        }
            
    }
    public function updateSettingsAction($price, $stock)
    {
           
        $value=Settings::find('id=1');
        $value[0]->price=$price;
        $value[0]->stock=$stock;
        $value[0]->update();
        echo "Updated";
        die();
    }
    public function getProductAction()
    {
       $products=Products::count('stock<10');
     
            echo "Product Count whose stock is less than 10 = ". $products;
            die;
        //    if($val->st)
    }
    public function deleteAclAction()
    {
        $acl = scandir(APP_PATH."/security/", 1);
        foreach($acl as $val)
        {
            if($val!='..' && $val!='.')
            {
                unlink(APP_PATH."/security/$val");
            }
        }
        echo "All Acl File Deleted";
        die();

    }
    public function getDateAction()
    {
        $current_date = Date('y-m-d');
        $order =  Orders::findFirst([
            'conditions'=>'datetime=:current_date:',
            'bind'=>[
                'current_date'=>$current_date
            ],
            'order' => 'datetime DESC'
        ]);
        
        echo "Order ID = ".$order->orderid;
        echo "Customer Name =".$order->name;
        echo "Customer Address =".$order->address;
        echo "Zipcode =".$order->zipcode;
        echo "Product Name = ".$order->dropdown;
        echo "Order Quantity =".$order->quantity;
    }
}
