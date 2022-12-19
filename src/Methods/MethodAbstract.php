<?php
    declare(strict_types=1);

    namespace Router\Methods;

    use Router\RouteInterface;

    /**
     * Abstract class with required functionality for request methods
     * 
     * @var string $routePath URI route path to register
     * @var mixed $routeController Route data to register with path
     * @var array $routeParams Parameters data which has positions and name of parameter
     * 
     * @author Hexan45
     */
    abstract class MethodAbstract implements RouteInterface {
        public string $routePath;
        public array $routeParams;
        public mixed $routeController;


        /**
         * 
         * @param string $routePath equals to class variable named routePath
         * @param callable|string|array $routeController equals to class variable named routeController
         * 
         */
        public function __construct(string $routePath, callable|array|string $routeController) {
            $this->routePath = $routePath;
            $this->routeController = $routeController;
            $this->routeParams = [];
        }

        /**
         * 
         * Preparing route data for use in route
         * 
         * @return self
         * 
         */
        public function prepare() : self {
            //Preparing route controller is in format like this 'Controller@method'
            if(is_string($this->routeController)) $this->routeController = explode('@', $this->routeController, 2);

            //Convert routePath into array
            $explodedPath = explode('/', $this->routePath);
            array_shift($explodedPath);
            
            //Iterate over paths
            for($__i__ = 0, $length = count($explodedPath); $__i__ < $length; $__i__++) {

                //Check if this uri part expects parameter
                if (preg_match('/\$[a-zA-Z0-9\_\-]+/', $explodedPath[$__i__])) {
                    //Add new parameter for dynamic uri in array which key is position in uri and value is name of parameter
                    $this->routeParams[$__i__] = str_replace('$', '', $explodedPath[$__i__]);
                    //Change part of url which represents parameter in uri to regular expression
                    $explodedPath[$__i__] = preg_replace('/\$[a-zA-Z0-9\_\-]+/', '([a-zA-Z0-9\-\_]+)', $explodedPath[$__i__]);
                }

            }

            //Save route path as regular expression
            $this->routePath = '@^\/' . implode('\/', $explodedPath) . '$@D';

            return $this;
        }
    }