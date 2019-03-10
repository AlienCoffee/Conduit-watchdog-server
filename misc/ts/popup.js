define(["require", "exports", "./common"], function (require, exports, common_1) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    function addPopupTile(title, message, style = "", timeout) {
        var tile = document.createElement("div");
        tile.classList.add("popup-tile", style);
        var _title = document.createElement("h4");
        _title.classList.add("popup-title");
        tile.appendChild(_title);
        _title.innerHTML = title;
        var _message = document.createElement("p");
        _message.classList.add("popup-message");
        tile.appendChild(_message);
        _message.innerHTML = message;
        common_1.element("popup").prepend(tile);
        setTimeout(function () {
            common_1.element("popup").removeChild(tile);
        }, timeout * 1000);
    }
    exports.addPopupTile = addPopupTile;
    function addErrorPopupTile(title, message, timeout) {
        addPopupTile(title, message, "popup-error", timeout);
    }
    exports.addErrorPopupTile = addErrorPopupTile;
    function addShortErrorPopupTile(title, message) {
        addErrorPopupTile(title, message, 5);
    }
    exports.addShortErrorPopupTile = addShortErrorPopupTile;
});
//# sourceMappingURL=popup.js.map