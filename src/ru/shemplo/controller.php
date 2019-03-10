<?php

class MainController {

    public function handleIndexPage (
        $context,
        $paths      = ["/", "/index"],
        $method     = "GET",
        $authorized = false
    ) {
        if (!$context ["user"]["authorized"]) {
            return new PageHolder ("login", $context);
        }

        return new PageHolder ("console", $context);
    }

    public function handleLoginAttempt (
        $context,
        $paths      = ["/watchdog/user/login"],
        $method     = "POST",
        $authorized = false
    ) {
        $data = json_decode ($context ["data"], true);

        $token = try_authorize ($data ["login"], $data ["password"]);
        if ($token) {
            setcookie ("session", $token, time () + 60 * 60 * 1, "/");
            return new AuthVerdict (true, "");
        }

        return new AuthVerdict (false, "Wrong login or password");
    }

}

?>