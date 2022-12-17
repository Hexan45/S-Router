<?php
    declare(strict_types=1);

    namespace Router\Methods;

    use Router\RouteInterface;

    /**
     * Abstract class with required functionality for request methods
     * 
     * @var string $routePath URI route path to register
     * @var mixed $routeController Route data to register with path
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

            return $this;
        }
    }