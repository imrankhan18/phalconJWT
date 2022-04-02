<?php
require_once'../vendor/autoload.php';
// print_r(apache_get_modules());
// echo "<pre>"; print_r($_SERVER); die;
// $_SERVER["REQUEST_URI"] = str_replace("/phalt/","/",$_SERVER["REQUEST_URI"]);
// $_GET["_url"] = "/";
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Di;
use Phalcon\Escaper;
// use Phalcon\Session;
// use Phalcon\Http\Response\Cookies;
use Phalcon\Logger;
use Phalcon\Events\Manager;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Logger\Adapter\Stream;
use App\Locale\Locale;

$config = new Config([]);

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . "/controllers/",
        APP_PATH . "/models/",
        APP_PATH . "/"
    ]
);
$loader->registerNamespaces(
    [
        
        'App\Handle'=> APP_PATH . "/handle",
        'App\Listener'=> APP_PATH . "/listener",
        'App\Locale'=> APP_PATH . "/locale"
    ]
);


$loader->register();

$container = new FactoryDefault();

$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);

$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);
$application = new Application($container);

$eventsManager = new EventsManager();

$eventsManager->attach('Handle', new \App\Handle\Handle() );



$eventsManager->attach(
    'application:beforeHandleRequest',
    new \App\Listener\NotificationListener()
);
// $application->setEventsManager($eventsManager);
$container->set(
    'EventsManager',
    $eventsManager
);
$eventsManager->fire("event:default", new \App\Handle\Handle );



// $container->set(
//     "session",
//     function()
//     {
//         $session = new Manager();
//         $files = new Stream(
//             [
//                 'savePath' => '/tmp',
//             ]
//         );
//         $session->setAdapter($files);
//         $session->start();

//         return $session;


//     }
// );

// $container->set(
//     "cookies",
//     function()
//     {
        
//         $cookies=new Cookies();
//         $cookies->useEncryption(false);
//         return $cookies;
//     }
// );

// 
// $container = new FactoryDefault();
$container->set(
    'db',
    function() {
        $eventsManager = new Manager();
        $adapter = new Stream('../app/logs/db.log');
        $logger  = new Logger(
            'messages',
            [
                'main' => $adapter,
            ]
        );

        // $eventsManager-> attach(
        //     'db:afterQuery',
        //     function ($event, $connection) use ($logger) {
        //         $logger->info(
        //             $connection->getSQLStatement()
        //         );
        //     }
        // );

        $connection = new Mysql(
            [
                'host'     => 'mysql-server',
                'username' => 'root',
                'password' => 'secret',
                'dbname'   => 'awt',
            ]
        );

        $connection->setEventsManager($eventsManager);

        return $connection;
    }
);

// 





// $container->set(
//     'db',
//     function () {
//         return new Mysql(
//             [
//                 'host'     => 'mysql-server',
//                 'username' => 'root',
//                 'password' => 'secret',
//                 'dbname'   => 'phalt',
//                 ]
//             );
//         }
// );





// $container = new Di();

$container->set(
    'escaper',
    function ()
    {
        return new Escaper();
    }
);

$loader = new Loader();
$loader-> registerDirs(
    [
        APP_PATH . "/controller/",
        APP_PATH . "/models/"
    ]
);
$loader->registerNameSpaces(
    [
        'App\Components' => APP_PATH . "/components"
    ]
);
$loader->register();

// $container = new Di();

$container->set(
    'logger',
    function () {
        $adapter = new Stream('/app/components/main.log');
        $logger  = new Logger(
            'messages',
            [
                'main' => $adapter,
            ]
        );

        return $logger;
    }
);

$container->set('locale', (new Locale())->getTranslator());

// $container->set(
//     'mongo',
//     function () {
//         $mongo = new MongoClient();

//         return $mongo->selectDB('phalt');
//     },
//     true
// );
try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}
