<?php
namespace twigviewengine {

    use compot\IViewEngine;

    class TwigViewEngine implements IViewEngine{

        public function render($template, $data)
        {
            return "yami";
        }
    }
}