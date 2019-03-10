define(["require", "exports"], function (require, exports) {
    "use strict";
    Object.defineProperty(exports, "__esModule", { value: true });
    function element(id) {
        return document.getElementById(id);
    }
    exports.element = element;
    function dataElement(id) {
        return element(id);
    }
    exports.dataElement = dataElement;
});
//# sourceMappingURL=common.js.map