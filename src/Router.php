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
            'GET' => [],
            'POST' => []
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
            self::$registeredRoutes[$routeInstance::ROUTE_METHOD][$routeInstance->routePath] = [
                $routeInstance->routeController,
                $routeInstance->routeParams
            ];
        }

        /**
         * 
         * This method prepare dynamic route parameters for final controller
         * 
         * @param array|callable $controller Controller data
         * @param ?array $params Optional route parameters
         * @param Request $request Object which has data about request
         * 
         */
        static private function call(array|callable $controller, ?array $params = null, Request $request) : void {
            //Change string to array, this is a uri entered by client
            $requestParts = explode('/', $request->uri);
            array_shift($requestParts);

            //Search and get parameters from request object
            $params = array_flip($params);
            foreach($params as $key => $value) {
                $params[$key] = $requestParts[$value];
            }

            //Call to class and method or function specified while creating route
            call_user_func_array(((is_array($controller)) ? [new $controller[0], $controller[1]] : $controller), $params);
        }

        /**
         * 
         * This methods search for registered route by compare uri regular expression to request uri and calling to function named call
         * with route data. If route not exists then throwing new exception
         * 
         */
        static public function resolve() : void {
            $request = new Request();

            //Iterate on all registered routes
            foreach(self::$registeredRoutes[$request->method] as $routeURI => $routeData) {
                //Compase regular expression with request uri
                if(preg_match($routeURI, preg_quote($request->uri), $matches)) {
                    //If match has exists call to function
                    self::call($routeData[0], $routeData[1], $request);
                    return;
                }
            }

            //Throw route not found exception
            throw new RouteNotFoundException();
        }
        
    }