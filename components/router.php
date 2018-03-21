<?php

class Router
{
    private $routes;

    public function __construct()
    {
        $routerPath = ROOT.'/config/routes.php';
        $this->routes = include ($routerPath);
    }

    // Метод возвращает строку (Приватный - потому что будем работать только из этого класса)
    private function getURI()
    {
        if(!empty($_SERVER['REQUEST_URI'])) {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }

    public function run()
    {
        // Получаем строку запроса от пользователя
        $uri = $this->getURI();

        // Проверяем наличие такого запроса в routes.php
        foreach ($this->routes as $uriPattern => $path){

            // Сравниваем $uriPattern и $uri
            if(preg_match("~$uriPattern~", $uri)) {

                // Определяем какой контроллер и какой action обрабатывают запрос пользователя
                $segments = explode('/', $path);

                $controllerName = array_shift($segments).'Controller';
                $controllerName = ucfirst($controllerName);

                $actionName = 'action' . ucfirst(array_shift($segments));
                //echo '<br>Класс: '.$controllerName;
                //echo '<br>Метод: '.$actionName;

                // Подключение файл класса-контроллера
                $controllerFile = (ROOT . '/controllers/' . $controllerName . '.php');
                //var_dump($controllerFile);
                if(file_exists($controllerFile)) {
                    include_once($controllerFile);
                }

                // Создаем объект класса контроллера, вызывает метод (т.е. action)
                $controllerObject = new $controllerName;
                $result = $controllerObject->$actionName();
                if($result != null){
                    break;
                }
            }

        }
    }
}