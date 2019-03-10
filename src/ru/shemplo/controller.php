<?php

class MainController {

    public function handleIndexPage (
        $context,
        $paths      = ["/", "/index"],
        $method     = "GET",
        $authorized = false
    ) {
        if (!$context ["authorized"]) {
            return new PageHolder ("login", $context);
        }

        return new PageHolder ("console", $context);
    }

    public function handleLoginAttempt (
        $context,
        $paths      = ["/watchdog/user/login"],
        $method     = "GET",
        $authorized = false
    ) {
        // do something here
    }

}

?>