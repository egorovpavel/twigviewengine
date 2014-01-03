<?php
namespace twigviewengine {

    use compot\HttpApplication;
    use compot\IViewEngine;

    class TwigViewEngine implements IViewEngine
    {

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

        public function __construct($viewPath, HttpApplication $app)
        {
            $this->app = $app;
            $this->loader = new \Twig_Loader_Filesystem([strtolower($viewPath)]);
            $this->twig = new \Twig_Environment($this->loader, [
                //'cache' => strtolower($viewPath) . '/cache',
                'debug' => true
            ]);


            $this->twig->addFunction(new \Twig_SimpleFunction('urlFor', function ($routeName, $action = null, $controller = null, $arguments = []) use ($app) {
                $route = $app->getRoute($routeName);
                return $route->generateFor($controller, $action, $arguments);
            }));

            $this->twig->addFunction(new \Twig_SimpleFunction('validationMessageFor', function ($context, $property) use ($app) {
                if (isset($context['_errors'])) {
                    foreach ($context['_errors'] as $violation) {
                        if ($violation->getPropertyPath() == $property) {
                            return $violation->getMessage();
                        }
                    }
                } else {
                    return null;
                }
                return "";
            }, ['needs_context' => true]));

            $this->twig->addFunction(new \Twig_SimpleFunction('isValid', function ($context, $property, $onTrue, $onFalse) use ($app) {
                if (isset($context['_errors'])) {
                    foreach ($context['_errors'] as $violation) {
                        if ($violation->getPropertyPath() == $property) {
                            return $onFalse;
                        }
                    }
                } else {
                    return null;
                }
                return $onTrue;
            }, ['needs_context' => true]));

        }

        public function render($template, $data)
        {
            return $this->twig->render(strtolower($template) . '.twig', $data);
        }
    }
}