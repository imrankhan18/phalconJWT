<?php
use MyApp\Locale;
use Phalcon\Mvc\Controller;
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;
use Phalcon\Acl\Component;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Phalcon\Translate\Adapter\NativeArray;
use Phalcon\Translate\InterpolatorFactory;
use Phalcon\Translate\TranslateFactory;

class AclController extends Controller
{
    public function indexAction()
    {
        
    }
    public function createTokenAction()
    {
       $userd=$_POST;
        $signup = new Signup();
        $signup->assign(
            $this->request->getPost(),
            ['dropdown', 'email' , 'name']
        );
            $signup->token=$this->tokenByThirdParty($_POST['name'], $_POST['dropdown']);
            $signup->save();
            $this->response->redirect('signup');

    }
    public function aclAction()
    {

        
       
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
    public function tokenByThirdParty($name, $role)
    {


        $key = "example_key";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "name" => $name,
            "sub" => $role
        );


        $jwt = JWT::encode($payload, $key, 'HS256');
    //     echo $jwt;
    //     die();
         return $jwt;
    }
    public function langTranslateAction()
    {
          
           
    }
}
