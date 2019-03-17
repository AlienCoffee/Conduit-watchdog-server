import { Request, PostRequest, GetRequest, Verdict } from "./network";
import { dataElement } from "./common";
import { ErrorPopupTile } from "./popup";

export function attemptLogin () {
    var login    = dataElement ("login").value;
    var password = dataElement ("password").value;

    if (login && password) {
        var request : Request = new PostRequest ("/watchdog/user/login", {
            "login": login, "password": password
        });

        request.send (onResponse);
    } else {
        new ErrorPopupTile ("Empty fields", 
                "Login or password field is empty", 5)
                .show ();
    }
}

export function onResponse (response : Verdict) {
    if (!response.verdict) { 
        new ErrorPopupTile ("Authentification failed", 
                response.message, 10).show ();
    } else { location.reload (); }
}