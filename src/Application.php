<?php

namespace Website;

class Application
{
    /** @var \Minz\Engine **/
    private $engine;

    public function __construct()
    {
        // Initialize the routes
        $router = new \Minz\Router();
        $router->addRoute('get', '/', 'home#index');
        $router->addRoute('get', '/cagnotte', 'payments#init');
        $router->addRoute('post', '/cagnotte', 'payments#pay');
        $router->addRoute('get', '/merci', 'payments#succeeded');
        $router->addRoute('get', '/annulation', 'payments#canceled');

        $router->addRoute('post', '/stripe/hooks', 'stripe#hooks');

        $router->addRoute('cli', '/system/init', 'system#init');

        $this->engine = new \Minz\Engine($router);
        \Minz\Url::setRouter($router);
    }

    public function run($request)
    {
        \Minz\Output\View::declareDefaultVariables([
            'errors' => [],
        ]);

        return $this->engine->run($request);
    }
}
