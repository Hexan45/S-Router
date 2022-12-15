<?php
    declare(strict_types=1);

    namespace Router;

    /**
     * Primary route interface for creating router methods
     * 
     * @author Hexan45
     */
    interface RouteInterface {
        public function __construct(string $routePath, callable|array|string $routeController);
        public function prepare() : self;
    }