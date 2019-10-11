<?php


class Router
{
  private $routes;

  /**
   * Router constructor.
   *
   */
  public function __construct()
  {
    $routesPath = ROOT.'/config/routes.php';
    $this->routes = include($routesPath);
  }

  /**
   *
   */
  public function run()
  {
    /*echo 'class '.__CLASS__.' , method '.__FUNCTION__;
    print_r($this->routes);*/
    $uri = $this->getURI();
    //echo $uri;
    foreach ($this->routes as $uriPattern => $route) {
      if (preg_match("~$uriPattern~", $uri)) {
          $segments = explode('/',$route);
         /*get controller & method names*/
          $controllerName = ucfirst(array_shift($segments).'Controller');
          $actionName = 'action'.ucfirst(array_shift($segments));
          /*print_r($controllerName);
          print_r($actionName);*/

          $controllerFile = ROOT.'/controllers'.$controllerName.'php';
          if (file_exists($controllerFile)){
              include_once($controllerFile);
          }
          $controllerObject = new $controllerName;
          $result = $controllerObject->$actionName();
          if ($result != null){
              break;
          }

      }
    }
      include ROOT."/view.php";
  }

  /**
   * @return URI string
   */
  private function getURI(): string
  {
    if (!empty($_SERVER['REQUEST_URI'])) {
      return trim($_SERVER['REQUEST_URI'], '/');
    }
    return '';
  }


}