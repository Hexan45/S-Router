<?php
    declare(strict_types=1);

    namespace Router;

    use Router\Router;

    /**
     *
     * Route class which has used to creating new route in application
     * 
     * @author Hexan45
     */
    class Route {
        /**
         * Method sending prepared instance of methods class to static Router and process exceptions if they occur
         * 
         * @param RouteInterface $routeInstance Instance of route methods class like GET/POST which described interface named RouteInterface
         * 
         */
        private function sendToRouter(RouteInterface $routeInstance) : void {
            try {
                Router::registerRoute($routeInstance->prepare());
            } catch(\Throwable $exception) {
                echo '<b>Router exception occurred:</b> ' . $exception->getMessage() . 'Code: ' . $exception->getLine() . ' ' . $exception->getFile();
            }
        }

        /**
         * Method call to methods class and craete instance
         * 
         * @param string $name Name of searched class
         * @param array $arguments Parameters to inject into class constructor
         * 
         */
        public function __call($name, $arguments) : void{
            $name = __NAMESPACE__ . '\\' . 'Methods' . '\\' . $name;
            $this->sendToRouter(new $name($arguments[0], $arguments[1]));
        }

        /**
         * If all routes has registered run resolve method from Router class
         * 
         */
        public function __destruct() {
            Router::resolve();
        }
    }