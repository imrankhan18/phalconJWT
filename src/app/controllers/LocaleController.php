<?php

use Phalcon\Mvc\Controller;


class LocaleController extends Controller
{
    public function indexAction()
    {
        echo "hello";
        $name = 'Mike';

        $text = $this->locale->_(
            'hi-name',
            [
                'name' => $name,
            ]
        );
       
        $this->view->text = $text;
        
    }
}