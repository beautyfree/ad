<?php

class ActionController {
    protected static $sCurrentControllerPath=null;
    protected static $sCurrentAction=null;
    protected static $sActionTemplate=null;

    public $parameters = array();

    public function __construct($sControllerPath,$sAction) {
        self::$sCurrentAction = $sAction;
        self::$sCurrentControllerPath = $sControllerPath;
    }

    public function GetTemplate() {
        if (is_null(self::$sActionTemplate)) {
            $this->SetTemplateAction(self::$sCurrentControllerPath.'/'.self::$sCurrentAction);
        }
        return self::$sActionTemplate;
    }

    public static function GetControllerPath() {
        return self::$sCurrentControllerPath;
    }

    public static function SetTemplateAction($sTemplate) {
        //$sActionTemplatePath = 'views/'.$sTemplate.'.php';
        self::$sActionTemplate = $sTemplate;
    }

    public function Render() {
        $sPath = $this->GetTemplate();

        # Renders a template relative to app/views
        $sPath = "/var/www/ad/app/views/".$sPath.".php";

        //error_log("render_file() path:$path");
        if(file_exists($sPath)) {
            # Pull all the class vars out and turn them from $this->var to $var
            if(is_object($this)) {
                $aControllerLocals = get_object_vars($this);
            }
            $aLocals = array(); // Должны передаваться в рендер со стороны экшена
            if(is_array($aControllerLocals)) {
                $aLocals = array_merge($aControllerLocals, $aLocals);
            }
            if(count($aLocals)) {
                foreach($aLocals as $sTmpKey => $sTmpValue) {
                    ${$sTmpKey} = $sTmpValue;
                }
                unset($sTmpKey);
                unset($sTmpValue);
            }
            include($sPath);
            return true;
        }
    }
}
