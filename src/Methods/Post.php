<?php
    declare(strict_types=1);

    namespace Router\Methods;

    use Router\Methods\MethodAbstract;

    /**
     * 
     * Get class for register get type method route in application
     *
     * @var const string Describe which method representing this class
     * 
     * @author Hexan45
     */
    class Post extends MethodAbstract {
        public const ROUTE_METHOD = 'POST';
    }