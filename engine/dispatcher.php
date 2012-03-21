<?php

class Dispatcher {

    public function dispatch() {

        // Фильтр
        // Сессия
        $oRouter = new Router();
        $oRouter->Exec();

    }

}
