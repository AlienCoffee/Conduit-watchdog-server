import { Request, PostRequest } from "./network";
import { dataElement } from "./common";
import { addShortErrorPopupTile } from "./popup";

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
    console.log (response);
}