<?php

class ActionController {
    protected static $sCurrentController=null;
    protected static $sCurrentAction=null;
    protected static $sActionTemplate=null;

    public $parameters = array();

    public function __construct($sController,$sAction) {
        self::$sCurrentAction = $sAction;
        self::$sCurrentController = $sController;
    }

    public function GetTemplate() {
        if (is_null(self::$sActionTemplate)) {
            self::SetTemplateAction(self::$sCurrentController.'/'.self::$sCurrentAction);
        }
        return self::$sActionTemplate;
    }

    public static function GetControllerPath() {
        return self::$sCurrentController;
    }

    public static function SetTemplateAction($sTemplate) {
        self::$sActionTemplate = $sTemplate;
    }
}
