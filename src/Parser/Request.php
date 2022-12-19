<?php
    declare(strict_types=1);

    namespace Router\Parser;

    /**
     * Request class, is intended to taking some data about request and prepare
     * 
     * @var string $method Request method
     * @var string $uri Request uri
     * @var string $domain Specifies protocol of request Http/Https
     * @var string $protocol Https protocol
     * 
     * @author Hexan45
     */
    class Request {
        public string $method;
        public string $uri;
        private string $domain;
        private string $protocol;

        /**
         * This method sanitize and checks if URI is valid
         * 
         * @params string $uri URI string
         * @return string Validated and sanitized uri
         */
        private function sanitizeURI(string $uri) : string {
            //Remove illegal characters from uri
            $uri = filter_var($this->protocol . '://' . $this->domain . $uri, FILTER_SANITIZE_URL);

            //Check if uri is valid
            if (filter_var($uri, FILTER_VALIDATE_URL)) {
                return parse_url($uri, PHP_URL_PATH);
            }
        }

        public function __construct() {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->domain = $_SERVER['HTTP_HOST'];

            //Specify request protocol
            $this->protocol = ((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] === 'on')) ? 'https' : 'http';

            $this->uri = $this->sanitizeURI($_SERVER['REQUEST_URI']);
        }
    }