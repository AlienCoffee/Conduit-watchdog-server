
var __page_context = {};

var loadContext = function (filenames) {
    for (var i = 0; i < filenames.length; i++) {
        filenames [i] = "misc/ts/" + filenames [i];
    }

    requirejs (filenames, function () { 
        for (var i = 0; i < arguments.length; i++) {
            var keys = Object.keys (arguments [i]);
            for (var j = 0; j < keys.length; j++) {
                __page_context [keys [j]] = arguments [i][keys [j]];
            }
        }
    });
}