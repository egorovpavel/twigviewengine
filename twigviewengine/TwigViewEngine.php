<?php
namespace twigviewengine {

    use compot\HttpApplication;
    use compot\IViewEngine;

    class TwigViewEngine implements IViewEngine{

        /**
         * @var \Twig_Loader_String
         */
        protected $loader;

        /**
         * @var \Twig_Environment
         */
        protected $twig;

        /**
         * @var HttpApplication
         */
        protected $app;

        public function __construct($viewPath, HttpApplication $app){
            $this->app = $app;
            $this->loader = new \Twig_Loader_Filesystem([strtolower($viewPath)]);
            $this->twig = new \Twig_Environment($this->loader, [
                'cache' => strtolower($viewPath) . '/cache',
                'debug' => true
            ]);


            $this->twig->addFunction(new \Twig_SimpleFunction('urlFor', function ($routeName, $action = null, $controller = null, $arguments = []) use($app){
                $route = $app->getRoute($routeName);
                return $route->generateFor($controller, $action, $arguments);
            }));

        }

        public function render($template, $data)
        {
            return $this->twig->render(strtolower($template). '.twig' , $data);
        }
    }
}