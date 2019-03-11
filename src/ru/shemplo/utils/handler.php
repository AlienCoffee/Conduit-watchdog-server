<?php

    function find_request_handler ($controller) {
        global $_request_arguments;
        global $_request_parsed;
        global $_request_method;
        global $_request_data;
        global $_request_time;
        global $_user;

        $class_ref = new ReflectionClass ($controller);
        foreach ($class_ref->getMethods () as $method_ref) {
            $request_context = Array (
                "paths"      => Array (),
                "method"     => "",
                "authorized" => false
            );

            fill_handler_context ($method_ref, $request_context);
            //print_r ($request_context); echo "<br />";

            $expected_method = strtoupper ($request_context ["method"]);
            if (!str_compare ($expected_method, $_request_method)) {
                continue; // Handler expects other request method
            }

            if (!check_handler_paths ($request_context)) {
                continue; // Handler is responsible for other paths
            }

            if ($request_context ["authorized"] && !$_user ["authorized"]) {
                continue; // This handler is avalilable for logged in user
            }

            $request_context ["user"] = $_user;
            $request_context ["data"] = $_request_data;
            $request_context ["time"] = $_request_time;
            $request_context ["request"] = $_request_parsed;
            $request_context ["arguments"] = $_request_arguments;

            $return = $method_ref->invoke ($controller, $request_context);

            if ($return instanceof PageHolder) {
                $return->load_page ();
            } else if ($return instanceof ObjectHolder) {
                echo $return->toJson ();
            } else if ($return) {
                echo $return;
            }
        }
    }

    function fill_handler_context ($method_ref, &$request_context) {
        foreach ($method_ref->getParameters () as $param_ref) {
            if (!$param_ref->isDefaultValueAvailable ()) {
                continue;
            }

            $name = $param_ref->getName ();
            $request_context [$name] = $param_ref->getDefaultValue ();
        }
    }

    function check_handler_paths ($request_context) {
        global $_request_parsed;

        $request_path = $_request_parsed ["path"];
        if (is_array ($request_context ["paths"])) {
            $flag = false;
            foreach ($request_context ["paths"] as $expected_path) {
                $expected_path = "/^".str_replace ("/", "\/", $expected_path)."$/";
                $flag = $flag || preg_match ($expected_path, $request_path);
                //echo "$flag ! $expected_path ! $request_path<br/>";
                if ($flag) { break; }
            }

            return $flag;
        } else if (!preg_match ($request_context ["paths"], $request_path)) {
            return false;
        }
    } 



    // Return types for handlers

    class PageHolder {

        private $filename, $context;

        public function __construct ($filename, $context) {
            $this->filename = $filename;
            $this->context = $context;
        }

        public function load_page () {
            $file = "src/ru/shemplo/front/".$this->filename.".php";
            require_once ($file);
        }

    }

    interface ObjectHolder {

        public function toJson ();

    }

    class Verdict implements ObjectHolder {

        private $verdict, $message;

        public function __construct ($success, $message) {
            $this->verdict = $success; $this->message = $message;
        }

        public function toJson () {
            return json_encode (Array ("verdict" => $this->verdict, 
                                       "message" => $this->message));
        }

    }

?>