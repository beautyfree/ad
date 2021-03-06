    <?php

/**
    GET     /photos             index   отображает список всех фото
    GET     /photos/new         new     возвращает форму HTML для создания нового фото
    POST    /photos             create  создает новое фото
    GET     /photos/:id         show    отображает определенное фото
    GET     /photos/:id/edit    edit    возвращает форму HTML для редактирования фото
    PUT     /photos/:id         update  обновляет определенное фото
    DELETE  /photos/:id         destroy удаляет определенное фото
*/

class Router {

    /**
     * Маршруты
     */
    protected $aRoutes = array();
    protected $oController;

    protected $oView; // Вынести

    static protected $sController=null;
    static protected $sAction=null;
    static protected $aObjects=array();
    static protected $aParameters=array();

    public function __construct() {
        $this->LoadRouter();

        $this->oView = new ActionView();
    }

    /**
     * Загружаем маршруты из конфигурационного файла
     */
    protected function LoadRouter() {
        $this->aRoutes = include('../config/routes.php');
    }

    public function Exec() {
        $this->ParseUrl();
        $this->ExecAction();
        $this->Shutdown();
    }

    protected function ParseUrl() {
        $sReq = $this->GetRequestUri();
        $this->SetRequestArray($sReq);
    }

    protected function GetRequestUri() {
        $sReq=preg_replace("/\/+/",'/',$_SERVER['REQUEST_URI']);
        $sReq=preg_replace("/^\/(.*)\/?$/U",'\\1',$sReq);
        $sReq=preg_replace("/^(.*)\?.*$/U",'\\1',$sReq);
        $sReq=mb_strtolower($sReq);

        return $sReq;
    }

    protected function SetRequestArray($sReq) {
        $aPathComponents = explode('/', $sReq);

        $iOffsetUrl = 1;
        for($i=0;$i<$iOffsetUrl;$i++) {
            array_shift($aPathComponents);
        }

        // Обрабатывает запрос стартовой страницы
        if (count($aPathComponents) == 0) {
            self::$sAction = 'index';
            self::$sController = 'home';
            return true;
        }

        // Пробегаем по роутам которые мы описали в route.php и пытаемся найти единственный, который удовлятворяет запросу
        foreach ($this->aRoutes as $sRoute => $sController) {
            $aRouteComponents = explode('/',$sRoute);
            array_shift($aRouteComponents);

            $sAction = 'index';
            $i=0;
            $aObjects = array();
            $bGoodRoute = true;
            $aPathComponents = array_pad($aPathComponents, count($aRouteComponents), '');
            $aParameters = array();

            // Обрабатываем роуты которые вызывают специфичные экшены
            $aControllerAction = explode(':',$sController);
            $sController = $aControllerAction[0];
            if (count($aControllerAction) == 2) {
                $sAction = $aControllerAction[1];
            }

            // Пробегаем по каждому компонету текущего роута пока не найдем часть которая не подходить или пробежимся по всем компанентам url
            foreach ($aRouteComponents as $sRouteComponent) {
                // Параметр
                if (substr($sRouteComponent,0,1) == ':') {
                    $aParameters[substr($sRouteComponent,1)] = $aPathComponents[$i];
                }

                // Экшен для котроллера
                elseif ($sRouteComponent == '[action]') {
                    if ($aPathComponents[$i] != '') {
                        self::$sAction = str_replace('-','_',$aPathComponents[$i]);
                    }
                }

                // Эта часть маршрута потребует, чтобы мы создали объект
                elseif (substr($sRouteComponent,0,1) == '(' && substr($sRouteComponent,-1,1) == ')') {
                    $oReflection = new ReflectionClass(substr($sRouteComponent,1,strlen($sRouteComponent)-2));
                    $aObjects[] = $oReflection->newInstanceArgs(array($aPathComponents[$i]));
                }

                // Если не к чему из этого не подошло то это неправильный роут
                elseif ($sRouteComponent != $aPathComponents[$i] && str_replace('-','_',$sRouteComponent) != $aPathComponents[$i]) {
                    //echo "Bad match: ".str_replace("-","_",$sRouteComponent)." != ".$aPathComponents[$i]."<br />";
                    $bGoodRoute = false;
                    break;
                }
                $i++;
            }

            //Этот роут удовлетворяет нашему запросу, получим котроллер работающего над ним
            if ($bGoodRoute && ($i >= count($aPathComponents) || $aPathComponents[$i] == "")) {
                self::$sController = $sController;
                self::$sAction     = $sAction;
                self::$aObjects    = $aObjects;
                self::$aParameters = $aParameters;
                return true;
            }
        }

        error_404();
    }

    protected function ExecAction() {

        //We treat 'new' the same as 'edit', since they generally contain a lot of the same code
        if (self::$sAction == "new")
            //self::$sAction = "edit";
            self::$sAction = "add";

        // Ищем контроллер
        $sControllerPath = "/var/www/ad/app/controllers/".self::$sController."_controller.php";
        if (file_exists($sControllerPath)) {
            require_once($sControllerPath);

            $aClassPathComponents = explode("/",self::$sController);
            $sClass = $aClassPathComponents[count($aClassPathComponents)-1];
            $sClass = ucfirst($sClass);
            $sClass = preg_replace_callback('/_([a-z])/', create_function('$c', 'return strtoupper($c[1]);'), $sClass);
            $sControllerClass = $sClass."Controller";

            $this->oController = new $sControllerClass(self::$sController,self::$sAction);
            $this->oController->parameters = self::$aParameters;

            foreach(self::$aObjects as $oObject) {
                $sName = strtolower(get_class($oObject));
                $this->oController->$sName = $oObject;
            }
            call_user_func_array(array($this->oController,self::$sAction),self::$aObjects);

            return true;
        }
    }

    protected function Shutdown() {
        $this->oView->Display($this->oController);
    }
}

