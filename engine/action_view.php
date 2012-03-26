<?php

class ActionView {
    protected $templates=null;

    /**
     * Инициализация
     */
    public function __construct() {
        $loader = new Twig_Loader_Filesystem('/var/www/ad/app/views');
        $this->templates = new Twig_Environment($loader, array(
            'cache' => '/var/www/ad/tmp',
            'autoescape' => FALSE
        ));
    }

    public function GetTwig() {
        return $this->templates;
    }
}
