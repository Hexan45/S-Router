<?php
    declare(strict_types=1);

    namespace Router;

    //[AdminController::class, 'methodName']
    //'AdminController@methodName'
    //Closure

    use Router\Exceptions\RouteHasRegisteredException;
    use Router\Exceptions\ControllerNotExistsException;
    use Router\Exceptions\ControllerMethodNotExistsException;
    use Router\Exceptions\RouteNotFoundException;

    class router {
        static private array $registeredRoutes = [
            'GET' => []
        ];
        static private object $request;

        static private function registerRoute(array $routeValues) : void {
            list($routePath, $routeMethod, $routeController) = $routeValues;

            if(array_key_exists($routePath, self::$registeredRoutes[$routeMethod])) {
                throw new RouteHasRegisteredException('Path named ' . $routePath . ' has exists in registered routes array');
            }

            if(!is_callable($routeController)) {
                list($controllerClass, $controllerMethod) = (is_string($routeController)) ? explode('@', $routeController) : $routeController;
    
                if(!class_exists($controllerClass)) {
                    throw new ControllerNotExistsException('Controller which your are trying register to route ' . $routePath . ' not exists');
                }
    
                if(!method_exists($controllerClass, $controllerMethod)) {
                    throw new ControllerMethodNotExistsException($controllerMethod . ' method not exists in ' . $controllerClass . ' controller');
                }

                $routeController = [$controllerClass, $controllerMethod];
            }

            self::$registeredRoutes[$routeMethod][$routePath] = $routeController;

        }

        static public function get(string $routePath, callable|array|string $routeController) : void {
            try {
                self::registerRoute([$routePath, 'GET', $routeController]);
            } catch(\Throwable $exception) {
                exit('Route error : ' . $exception->getMessage());
            }
        }

        static private function createRequestObject() : object {
            self::$request = new \stdClass();
            self::$request->method = $_SERVER['REQUEST_METHOD'];
            self::$request->uri = filter_var(strip_tags($_SERVER['REQUEST_URI']), FILTER_SANITIZE_URL);
            return self::$request;
        }

        static public function resolve() : void {
            $requestObject = self::createRequestObject();

            $controller = self::$registeredRoutes[$requestObject->method];
            if(!array_key_exists($requestObject->uri, $controller)) {
                throw new RouteNotFoundException();
            }

            call_user_func_array(
                ((is_array($controller[$requestObject->uri])) ?
                [new $controller[$requestObject->uri][0], $controller[$requestObject->uri][1]] :
                $controller[$requestObject->uri]),
            []);
        }
    }