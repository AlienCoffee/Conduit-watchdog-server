import { Request, PostRequest, GetRequest } from "./network";
import { dataElement } from "./common";
import { addShortErrorPopupTile, addErrorPopupTile } from "./popup";

export function attemptLogin () {
    var login    = dataElement ("login").value;
    var password = dataElement ("password").value;

    if (login && password) {
        var request : Request = new PostRequest ("/watchdog/user/login", {
            "login": login, "password": password
        });

        request.send (onResponse);
    } else {
        addShortErrorPopupTile ("Empty fields", 
           "Login or password field is empty");
    }
}

export function onResponse (response : string) {
    var data = <{"verdict" : boolean, "message" : string}> JSON.parse (response);
    if (!data.verdict) { 
        addErrorPopupTile ("Authentification failed", 
                           data.message, 10);
    } else { location.reload (); }
}