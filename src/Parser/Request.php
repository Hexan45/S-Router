<?php
    declare(strict_types=1);

    namespace Router\Parser;

    class Request {
        public string $method;
        public string $uri;
        private string $domain;
        private string $protocol;

        private function sanitizeURI(string $uri) : string {
            $uri = filter_var($this->protocol . '://' . $this->domain . $uri, FILTER_SANITIZE_URL);

            if (filter_var($uri, FILTER_VALIDATE_URL)) {
                return parse_url($uri, PHP_URL_PATH);
            }
        }

        public function __construct() {
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->domain = $_SERVER['HTTP_HOST'];
            $this->protocol = ((isset($_SERVER['HTTPS'])) && ($_SERVER['HTTPS'] === 'on')) ? 'https' : 'http';
            $this->uri = $this->sanitizeURI($_SERVER['REQUEST_URI']);
        }
    }