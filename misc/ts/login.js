define(["require", "exports", "./network", "./common", "./popup"], function (require, exports, network_1, common_1, popup_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    function attemptLogin() {
        var login = common_1.dataElement("login").value;
        var password = common_1.dataElement("password").value;
        if (login && password) {
            var request = new network_1.PostRequest("/watchdog/user/login", {
                "login": login, "password": password
            });
            request.send(onResponse);
        }
        else {
            popup_1.addShortErrorPopupTile("Empty fields", "Login or password field is empty");
        }
    }
    exports.attemptLogin = attemptLogin;
    function onResponse(response) {
        var data = JSON.parse(response);
        if (!data.verdict) {
            popup_1.addErrorPopupTile("Authentification failed", data.message, 10);
        }
        else {
            location.reload();
        }
    }
    exports.onResponse = onResponse;
});
//# sourceMappingURL=login.js.map