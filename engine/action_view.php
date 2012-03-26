<?php

class ActionView {
    protected $templates=null;
    protected $vars=array();

    /**
     * Инициализация
     */
    public function __construct() {
        $this->Init();
    }

    public function Init() {
        $loader = new Twig_Loader_Filesystem('/var/www/ad/app/views');
        $this->templates = new Twig_Environment($loader, array(
            'cache' => '/var/www/ad/tmp',
            'autoescape' => FALSE
        ));
    }

    public function Display($oController) {
        $sPath = $oController->GetTemplate();

        if(file_exists('/var/www/ad/app/views/'.$sPath.'.tpl')) {
            $oTemplate = $this->templates;

            if(is_object($this)) {
                $aControllerVars = get_object_vars($oController);
            }
            $aVars = $this->vars; // Должны передаваться в рендер со стороны экшена
            if(is_array($aControllerVars)) {
                $aVars = array_merge($aControllerVars, $aVars);
            }
            $sContent = $oTemplate->render($sPath.'.tpl',$aVars);

            echo $oTemplate->render('layouts/application.tpl',array('main_content'=>$sContent));

            return true;
        }
        return false;
    }

    public function Shutdown() {

    }
}
