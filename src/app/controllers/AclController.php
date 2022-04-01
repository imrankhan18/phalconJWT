<?php

use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

class AclController extends Controller
{
    public function indexAction()
    {
        // $this->view->acl=Permissions::find();
       

        // print_r($permissions);
        // die();
    }
    public function createTokenAction()
    {
        // print_r($_POST['dropdown']);
        // die;

        $signup = new Signup();
        $signup->assign(
            $this->request->getPost(),
            ['dropdown', 'email' , 'name']
        );

        // $tok=Signup::find();
        // $t['user']= $tok[0]->token;
        // print_r( );
        // die();
        
// Defaults to 'sha512'
        $signer  = new Hmac();

    // Builder object
        $builder = new Builder($signer);

        $now        = new DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+1 day')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';

        // Setup
        $builder
            ->setAudience('https://target.phalcon.io')  // aud
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($expires)               // exp 
            ->setId('123456789')                    // JTI id 
            ->setIssuedAt($issued)                      // iat 
            ->setIssuer('https://phalcon.io')           // iss 
            ->setNotBefore($notBefore)                  // nbf
            ->setSubject($_POST['dropdown'])   // sub
            ->setPassphrase($passphrase)                // password 
        ;
            $tokenObject = $builder->getToken();
            $signup->token=$tokenObject->getToken();
            $signup->save();
            $this->response->redirect('signup');
        //     print_r($signup);

}
    public function aclAction()
    {
        // $permissions= new Permissions();
        // $permissions->assign(
        //     $this->request->getPost(),
        //     ['dropdown', 'components' , 'action']
        // );
        // $permissions->save();

        // $drop=$this->request->getPost('dropdown');
        // $comp=$this->request->getPost('components');
        // $act=$this->request->getPost('action');  

       
        $aclfile = APP_PATH . '/security/acl.cache';
        if (true !== is_file($aclfile)) {
            $acl = new Memory();
            try {
                $parser = new Parser();
                $value = Signup::find();
                $tokenobject = $parser->parse($value[0]->dropdown);
                $now = new \DateTimeImmutable();
                $expire = $now->getTimestamp();
                $validator = new Validator($tokenobject, 100);
                $validator->validateExpiration($expire);
                $claims = $tokenobject->getClaims()->getPayload();
            } catch (\Exception $e) {
                echo $e->message;
                die;
            }
           
            $acl->addRole($claims['sub']);
            $acl->addComponent(
                'signup',
                [
                'index'
                ]
            );
            $acl->allow($claims['sub'], "*", "*");

            file_put_contents($aclfile, serialize($acl));
        } else {
            $acl = unserialize(file_get_contents($aclfile));
            $value = Signup::find();
            $parser = new Parser();
            for ($i = 0; $i < count($value); $i++) {
                $tokenobject = $parser->parse($value[$i]->dropdown);
                $now = new \DateTimeImmutable();
                $expire = $now->getTimestamp();
                $validator = new Validator($tokenobject, 100);
                $validator->validateExpiration($expire);
                $claims = $tokenobject->getClaims()->getPayload();
                $acl->addRole($claims['sub']);
                $acl->addComponent("signup", [
                    'index'
                ]);
                $acl->allow($claims['sub'], "signup", "index");
            }

            file_put_contents($aclfile, serialize($acl));
            
        }
    }
}
