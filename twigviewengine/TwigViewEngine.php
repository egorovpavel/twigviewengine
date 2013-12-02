<?php
namespace twigviewengine {

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

        public function __construct($viewPath){
            $this->loader = new \Twig_Loader_Filesystem([strtolower($viewPath)]);
            $this->twig = new \Twig_Environment($this->loader, [
                'cache' => strtolower($viewPath) . '/cache',
                'debug' => true
            ]);
        }

        public function render($template, $data)
        {
            return $this->twig->render(strtolower($template). '.twig' , $data);
        }
    }
}