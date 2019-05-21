<?php

    require_once ("src/configs.php");
    require_once ("src/ru/shemplo/utils/common.php");
    require_once ("src/ru/shemplo/utils/front.php");

    $_request_method = $_SERVER ['REQUEST_METHOD'];
    $_request_time   = $_SERVER ['REQUEST_TIME'];

    $_request_arguments = Array ();
    $_request_data = file_get_contents ('php://input');
    //* Можно не склеивать (см. ниже)
    /*$_request_url = join ("", [$_SERVER ['REQUEST_SCHEME'], "://",
               $_SERVER ['HTTP_HOST'], $_SERVER ['REQUEST_URI']]);
    */
    // Parsed on components URL address
    //* Частичный адрес тоже парсит
    $_request_parsed = parse_url ($_SERVER ['REQUEST_URI']);


    if (isset($_request_parsed['query'])){ //* Добавил
        // Getting all arguments after ? sign in request string
        //* Выдает предупреждение при включенном E_NOTICE в PHP
        parse_str ($_request_parsed ['query'], $_request_arguments);
    }

    $_user = Array ("authorized" => false);
    $_client_access_token = "guest";

    if (isset ($_COOKIE ["session"])) {
        $token = $_COOKIE ["session"];
        $keys = explode (":", $token);

        $admin_token = encrypt (SERVER_ACCOUNT_LOGIN, SERVER_ACCOUNT_PASSWORD);
        $_user ["authorized"] = str_compare ($token, $admin_token);
        if ($_user ["authorized"]) $_client_access_token = $token;
    }

    setcookie ("session", $_client_access_token,
               time () + 60 * 60 * 1, "/"); // 1 hour

    require_once ("src/ru/shemplo/utils/handler.php");
    require_once ("src/ru/shemplo/controller.php");

    $controller = new MainController ();
    find_request_handler ($controller);

    require_once ("src/ru/shemplo/watchdog.php");

    $controller = new WatchdogController ();
    find_request_handler ($controller);

?>
