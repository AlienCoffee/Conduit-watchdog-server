define(["require", "exports", "./popup"], function (require, exports, popup_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    class Request {
        constructor(method, url, data) {
            this.method = method;
            this.url = url;
            this.data = data;
        }
        ;
        send(handler) {
            var descriptor = new XMLHttpRequest();
            descriptor.open(this.method, this.url, true);
            descriptor.setRequestHeader("Content-Type", "application/json");
            descriptor.send(JSON.stringify(this.data));
            descriptor.onreadystatechange = function () {
                if (descriptor.readyState != 4) {
                    return;
                }
                if (descriptor.status >= 200 && descriptor.status < 300) {
                    handler(descriptor.responseText);
                }
                else {
                    var message = "Some error occured during connection to server (code "
                        + descriptor.status + ")";
                    popup_1.addShortErrorPopupTile("Request failed", message);
                }
            };
        }
    }
    exports.Request = Request;
    class PostRequest extends Request {
        constructor(url, data) {
            super("POST", url, data);
            this.url = url;
            this.data = data;
        }
        ;
    }
    exports.PostRequest = PostRequest;
    class GetRequest extends Request {
        constructor(url, data) {
            super("GET", url, data);
            this.url = url;
            this.data = data;
        }
        ;
    }
    exports.GetRequest = GetRequest;
});
//# sourceMappingURL=network.js.map