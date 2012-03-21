<?php

class Router {

    /**
     * Маршруты
     */
    protected $aRoutes = array();

    protected $oController;

    public function __construct() {
        $this->LoadRouter();
    }

    /**
     * Загружаем маршруты из конфигурационного файла
     */
    protected function LoadRouter() {
        $this->aRoutes = include('../app/config/routes.php');
    }

    public function Exec() {
        $this->ParseUrl();
        $this->Shutdown();
    }

    protected function ParseUrl() {
        $sReq = $this->GetRequestUri();
        $aRequestUrl = $this->GetRequestArray($sReq);
    }

    protected function GetRequestUri() {
        $sReq=preg_replace("/\/+/",'/',$_SERVER['REQUEST_URI']);
        $sReq=preg_replace("/^\/(.*)\/?$/U",'\\1',$sReq);
        $sReq=preg_replace("/^(.*)\?.*$/U",'\\1',$sReq);
        $sReq=mb_strtolower($sReq);

        return $sReq;
    }

    protected function GetRequestArray($sReq) {
        $aPathComponents = explode('/', $sReq);

        $iOffsetUrl = 1;
        for($i=0;$i<$iOffsetUrl;$i++) {
            array_shift($aPathComponents);
        }

        //default actions are called 'index'
        $action = "index";

        // Обрабатывает запрос стартовой страницы
        if (count($aPathComponents) == 0) {
            return $this->perform_controller_action("home",$action,array(),array());
        }

        // Пробегаем по роутам которые мы описали в route.php и пытаемся найти единственный, который удовлятворяет запросу
        foreach ($this->aRoutes as $sRoute => $sController) {
            $aRouteComponents = explode("/",$sRoute);
            array_shift($aRouteComponents);

            $sAction = "index";
            $i=0;
            $aObjects = array();
            $bGoodRoute = true;
            $aPathComponents = array_pad($aPathComponents, count($aRouteComponents), '');
            $aParameters = array();

            // Обрабатываем роуты которые вызывают специфичные экшены
            $aControllerAction = explode(":",$sController);
            $sController = $aControllerAction[0];
            if (count($aControllerAction) == 2) {
                $sAction = $aControllerAction[1];
            }

            // Пробегаем по каждому компонету текущего роута пока не найдем часть которая не подходить или пробежимся по всем компанентам url
            foreach ($aRouteComponents as $sRouteComponent) {
                // Параметр
                if (substr($sRouteComponent,0,1) == ":") {
                    $aParameters[substr($sRouteComponent,1)] = $aPathComponents[$i];


                // Экшен для котроллера
                } elseif ($sRouteComponent == "[action]") {
                    if ($aPathComponents[$i] != "") {
                        $sAction = str_replace("-","_",$aPathComponents[$i]);
                    }

                // Эта часть маршрута потребует, чтобы мы создали объект
                } elseif (substr($sRouteComponent,0,1) == "(" && substr($sRouteComponent,-1,1) == ")") {
                    $oReflection = new ReflectionClass(substr($sRouteComponent,1,strlen($sRouteComponent)-2));
                    $aObjects[] = $oReflection->newInstanceArgs(array($aPathComponents[$i]));

                // Если не к чему из этого не подошло то это неправильный роут
                } elseif ($sRouteComponent != $aPathComponents[$i] && str_replace("-","_",$sRouteComponent) != $aPathComponents[$i]) {
                    //echo "Bad match: ".str_replace("-","_",$sRouteComponent)." != ".$aPathComponents[$i]."<br />";
                    $bGoodRoute = false;
                    break;
                }
                $i++;
            }

            //Этот роут удовлетворяет нашему запросу, получим котроллер работающего над ним
            if ($bGoodRoute && ($i >= count($aPathComponents) || $aPathComponents[$i] == "")) {
                return $this->perform_controller_action($sController,$sAction,$aObjects,$aParameters);
            }
        }

        error_404();
    }

    //Look for a controller file matching the request, and failing that, a view
    protected function perform_controller_action($sClassPath,$sAction,$aObjects,$aParameters) {

        //We treat 'new' the same as 'edit', since they generally contain a lot of the same code
        if ($sAction == "new") {
            $sAction = "edit";
        }

        // Ищем контроллер
        $sControllerPath = "/var/www/ad/app/controllers/".$sClassPath."_controller.php";
        if (file_exists($sControllerPath)) {
            require_once($sControllerPath);

            $aClassPathComponents = explode("/",$sClassPath);
            $sClass = $aClassPathComponents[count($aClassPathComponents)-1];
            $sClass = ucfirst($sClass);

            $sClass = preg_replace_callback('/_([a-z])/', create_function('$c', 'return strtoupper($c[1]);'), $sClass);
            $sControllerClass = $sClass."Controller";

            if (!method_exists($sControllerClass,$sAction)) {
                if ($this->render_view($sClassPath,$sAction)) {
                    return false;
                } else {
                    fatal_error("$sControllerClass does not respond to $sAction");
                }
            }

            $this->oController = new $sControllerClass($sClassPath,$sAction);
            $this->oController->parameters = $aParameters;
            call_user_func_array(array($this->oController,$sAction),$aObjects);

            return true;
        }

        //If no controller was found, we'll look for a view
        if ($this->render_view($sClassPath,$sAction)) {
            return true;
        }
    }

    protected function render_view($sClassPath,$sAction) {
        $sViewPath = "/var/www/ad/app/views/$sClassPath/$sAction.php";
        if (file_exists($sViewPath)) {
            $oController = new ActionController();
            require_once($sViewPath);
            return true;
        }
        return false;
    }

    protected function Shutdown() {

        // Путь к шаблону
        $sTemplatePath = $this->oController->GetTemplate();

        /**
         * Подрубаем шаблон. Тут этого быть не должно
         */
        require_once($sTemplatePath);
    }
}

