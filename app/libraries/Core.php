<?php

    //Core App Class
    class Core{
        protected $currentController = 'Pages';
        protected $currentMethod = 'index';
        protected $params = [];

        public function __construct()
        {
            // print_r($this->getUrl());
            $url = $this->getUrl();
            //Look in 'controllers' for first ModelController.php file,
            //that matches name in URLe.g shop => Shop.php
            if (file_exists('../app/controllers/' . ucwords($url[0]). '.php')) {
                //Set a new controller
                $this->currentController = ucwords($url[0]);
                unset($url[0]);
            }
 
            //Require the Controller
            require_once '../app/controllers/' . $this->currentController . '.php';
            $this->currentController = new $this->currentController;
             
            //Check for second part of the URL
            if(isset($url[1])) {
                //Check if the method exists in the instanciated object of the controller
                //class e.g Shop.php index
                if(method_exists($this->currentController, $url[1])){
                //Set a new controller method
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                }

                //Get parameters
                $this->params = $url ? array_values($url) : [];

                //Call a callback with array of params
                call_user_func_array([$this->currentController, $this->currentMethod],
                $this->params);
            }
        }

        public function getUrl() {
            if (isset($_GET['url'])) {
                $url = rtrim($_GET['url'], '/');
                //Allows to filter variables as str/num i.e remove illegal chars e.g url = @,$
                $url = filter_var($url, FILTER_SANITIZE_URL);
                //Break url string into an array
                $url = explode('/',$url);
                return $url;
            }

        }
        
        
    }
