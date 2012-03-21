<?php

class ActionController {
    protected $sCurrentControllerPath=null;
    protected $sCurrentAction=null;
    protected $sActionTemplate=null;

    public $parameters = array();

    public function __construct($sControllerPath,$sAction) {
        $this->sCurrentAction = $sAction;
        $this->sCurrentControllerPath = $sControllerPath;
    }

    public function GetTemplate() {
        if (is_null($this->sActionTemplate)) {
            $this->SetTemplateAction($this->sCurrentControllerPath.'/'.$this->sCurrentAction);
        }
        return $this->sActionTemplate;
    }

    protected function SetTemplateAction($sTemplate) {
        $sActionTemplatePath = '/var/www/ad/app/views/'.$sTemplate.'.php';
        $this->sActionTemplate = $sActionTemplatePath;
    }
}
