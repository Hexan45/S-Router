<?php
    declare(strict_types=1);

    namespace Router;

    //Routing exceptions
    use Router\Exceptions\RouteHasRegisteredException;
    use Router\Exceptions\RouteNotFoundException;
    use Router\Exceptions\ControllerNotExistsException;
    use Router\Exceptions\ControllerMethodNotExistsException;

    //Request class for delivery request data
    use Router\Parser\Request;

    //Route interface for methods
    use Router\RouteInterface;

    /**
     * Router class for manage routes in application
     * 
     * @var array $registeredRoutes array of all registered routes data
     * 
     * @author Hexan45
     */
    class Router {
        static private array $registeredRoutes = [
            'GET' => []
        ];

        /**
         * This method has checking for errors and register route in array
         * 
         * @param RouteInterface $routeInterface Instance of route methods class like GET/POST which described interface named RouteInterface
         */
        static public function registerRoute(RouteInterface $routeInstance) : void {

            //Check for equls existing route in array if find throw exception
            if(array_key_exists($routeInstance->routePath, self::$registeredRoutes[$routeInstance::ROUTE_METHOD])) {
                throw new RouteHasRegisteredException('Path named ' . $routeInstance->routePath . ' has exists in registered routes array');
            }

            if(!is_callable($routeInstance->routeController)) {
                //Check if exists class controller which has assigned to route if not exists throw exception
                if(!class_exists($routeInstance->routeController[0]))
                    throw new ControllerNotExistsException('Controller which your are trying register to route ' . $routeInstance->routePath . ' not exists.');
                
                //Check if exists class method which has assigned to route if not exits throw exception
                if(!method_exists($routeInstance->routeController[0], $routeInstance->routeController[1]))
                    throw new ControllerMethodNotExistsException('Controller method named ' . $routeInstance->routeController[1] . ' not exists in ' . $routeInstance->routeController[0]);
            }

            //Join new route into all registered routes array
            self::$registeredRoutes[$routeInstance::ROUTE_METHOD][$routeInstance->routePath] = $routeInstance->routeController;
        }

        /**
         * 
         * This methods search for registered route using uri path entered by client and envoke controller if route not exists
         * in array throw new exception
         * 
         */
        static public function resolve() : void {
            $request = new Request();

            //Search for same route
            if(!array_key_exists($request->uri, self::$registeredRoutes[$request->method])) {
                throw new RouteNotFoundException();
            }

            $controller = self::$registeredRoutes[$request->method][$request->uri];
            //Call to class and method or function specified while creating route
            call_user_func_array(((is_array($controller)) ? [new $controller[0], $controller[1]] : $controller), []);
        }
        
    }