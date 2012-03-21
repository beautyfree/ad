<?php

class Router {

    /**
     * Маршруты
     */
    protected $aRoutes = array();

    public function __construct() {
        $this->LoadRouter();
    }



    protected function LoadRouter() {
        $this->aRoutes = include('../app/config/routes.php');
    }

    public function processRoute() {

        /**
         *
         */
        $sReq=preg_replace("/\/+/",'/',$_SERVER['REQUEST_URI']);
        $sReq=preg_replace("/^\/(.*)\/?$/U",'\\1',$sReq);
        $sReq=preg_replace("/^(.*)\?.*$/U",'\\1',$sReq);
        $sReq=mb_strtolower($sReq);

        $path_components = explode('/', $sReq);

        $iOffsetUrl = 1;
        for($i=0;$i<$iOffsetUrl;$i++) {
            array_shift($path_components);
        }

        //default actions are called 'index'
        $action = "index";

        // Обрабатывает запрос стартовой страницы
        if (count($path_components) == 0) {
            return Router::perform_controller_action("home",$action,array(),array());
        }

        // Пробегаем по роутам которые мы описали в route.php и пытаемся найти единственный, который удовлятворяет запросу
        foreach ($this->aRoutes as $route => $controller) {
            $route_components = explode("/",$route);
            array_shift($route_components);

            $action = "index";
            $i=0;
            $objects = array();
            $goodRoute = true;
            $path_components = array_pad($path_components, count($route_components), '');
            $parameters = array();

            // Обрабатываем роуты которые вызывают специфичные экшены
            $controller_action_array = explode(":",$controller);
            $controller = $controller_action_array[0];
            if (count($controller_action_array) == 2) {
                $action = $controller_action_array[1];
            }

            // Пробегаем по каждому компонету текущего роута пока не найдем часть которая не подходить или пробежимся по всем компанентам url
            foreach ($route_components as $route_component) {
                // Параметр
                if (substr($route_component,0,1) == ":") {
                    $parameters[substr($route_component,1)] = $path_components[$i];


                // Экшен для котроллера
                } elseif ($route_component == "[action]") {
                    if ($path_components[$i] != "") {
                        $action = str_replace("-","_",$path_components[$i]);
                    }

                // Эта часть маршрута потребует, чтобы мы создали объект
                } elseif (substr($route_component,0,1) == "(" && substr($route_component,-1,1) == ")") {
                    $reflection_obj = new ReflectionClass(substr($route_component,1,strlen($route_component)-2));
                    $object = $reflection_obj->newInstanceArgs(array($path_components[$i]));
                    $objects[] = $object;

                // Если не к чему из этого не подошло то это неправильный роут
                } elseif ($route_component != $path_components[$i] && str_replace("-","_",$route_component) != $path_components[$i]) {
                    //echo "Bad match: ".str_replace("-","_",$route_component)." != ".$path_components[$i]."<br />";
                    $goodRoute = false;
                    break;
                }
                $i++;
            }

            //Этот роут удовлетворяет нашему запросу, получим котроллер работающего над ним
            if ($goodRoute && ($i >= count($path_components) || $path_components[$i] == "")) {
                return Router::perform_controller_action($controller,$action,$objects,$parameters);
            }
        }

        error_404();
    }

    //Look for a controller file matching the request, and failing that, a view
    static function perform_controller_action($class_path,$action,$objects,$parameters) {

        //We treat 'new' the same as 'edit', since they generally contain a lot of the same code
        if ($action == "new") {
            $action = "edit";
        }

        // Ищем контроллер
        $sControllerPath = "/var/www/ad/app/controllers/".$class_path."_controller.php";
        if (file_exists($sControllerPath)) {
            require_once($sControllerPath);

            $class_path_components = explode("/",$class_path);
            $class = $class_path_components[count($class_path_components)-1];
            $class[0] = strtoupper($class[0]);
            $class= preg_replace_callback('/_([a-z])/', create_function('$c', 'return strtoupper($c[1]);'), $class);
            $controller_class = $class."Controller";

            if (!method_exists($controller_class,$action)) {
                if (Router::render_view($class_path,$action)) {
                    //exit;
                    return false;
                } else {
                    fatal_error("$controller_class does not respond to $action");
                }
            }

            $controller = new $controller_class();
            $controller->parameters = $parameters;
            call_user_func_array(array($controller,$action),$objects);
            //exit;
            return true;
        }

        //If no controller was found, we'll look for a view
        if (Router::render_view($class_path,$action)) {
            //exit;
            return true;
        }
    }

    static function render_view($class_path,$action) {
        $view_path = "/var/www/ad/app/views/$class_path/".$action.".php";
        if (file_exists($view_path)) {
            $controller = new ActionController();
            require_once($view_path);
            return true;
        }
        return false;
    }
}

