<?php
namespace App\Listener;

use Phalcon\Di\Injectable;
use Phalcon\Events\Events;
use Phalcon\Events as EventsManager;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

class NotificationListener extends Injectable
{

    public function beforeHandleRequest(\Phalcon\Events\Event $event, \Phalcon\Mvc\Application $application) 
    {

        
        $aclfile = APP_PATH . '/security/acl.cache';
        $controller = $this->router->getControllerName();
        $action = $this->router->getActionName();
        $bearer = $application->request->get('bearer');
        if ($bearer) {
            if (true === is_file($aclfile)) {
                $acl = unserialize(file_get_contents($aclfile));
                try {
                    $parser = new Parser();
                    $tokenobject = $parser->parse($bearer);
                    $now = new \DateTimeImmutable();
                    $expire = $now->getTimestamp();
                    $validator = new Validator($tokenobject, 100);
                    $validator->validateExpiration($expire);
                    $claims = $tokenobject->getClaims()->getPayload();
                
                } catch (\Exception $e) {
                    echo "bearer not find";
                    die;
                }
                if ($claims['sub'] == 'admin') {
                    $action = $this->router->getActionName();
                }

                if (true !== $acl->isAllowed($claims['sub'], "$controller", "$action")) {
                    echo "Access Denied";
                    // print_r($acl);
                    die();
                }
            }
        } else {
            echo "Can't find bearer:(";
            die;
        }
    }
}
