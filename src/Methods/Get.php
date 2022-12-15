<?php
    declare(strict_types=1);

    namespace Router\Methods;

    use Router\RouteInterface;

    /**
     * 
     * Get class for register get type method route in application
     * 
     * @var string $routePath URI route path to register
     * @var mixed $routeController Route data to register with path
     * @var const string Describe which method representing this class
     * 
     * @author Hexan45
     */
    class Get implements RouteInterface {
        public const ROUTE_METHOD = 'GET';

        public string $routePath;
        public mixed $routeController;

        /**
         * 
         * @param string $routePath equals to class variable named routePath
         * @param callable|string|array $routeController equals to class variable named routeController
         * 
         */
        public function __construct(string $routePath, callable|string|array $routeController) {
            $this->routePath = $routePath;
            $this->routeController = $routeController;
        }

        /**
         * 
         * Preparing route controller for use in route
         * 
         * @return self
         * 
         */
        public function prepare() : self {
            if(is_string($this->routeController)) $this->routeController = explode('@', $this->routeController);
            return $this;
        }
    }