import { ErrorPopupTile } from "./popup";
import { dataElement, inputElement } from "./common";
import { PostRequestWithFiles } from "./network";


export function initConsole () : void {
    dataElement ("watchdogUpdateFile").onchange = function (_ : Event) : void {
        var input = inputElement ("watchdogUpdateFile");
        var parent = input.parentElement;

        var status = parent.getElementsByClassName ("file-upload-selection") [0];
        if   (!input.value) { status.innerHTML = "file not selected"; } 
        else {
            var file = input.value.replace (new RegExp ("\\\\", "g"), "/")
                     . split ("/").reduce ((_, v) => v, "");
            var extension = file.split (".").reduce ((_, v) => v, "")
                          . toLowerCase ();
            console.log (extension);
            if (["zip", "gzip"].indexOf (extension) !== -1) {
                status.innerHTML = " " + file + " ";
            } else {
                new ErrorPopupTile ("Wrong file selected", 
                        "Only archives .zip and .gzip allowed", 5)
                        .show ();
                status.innerHTML = "file not selected";
                input.value = "";
            }
        }
    }
}

export function uploadWatchdogUpdate () : void {
    var input = inputElement ("watchdogUpdateFile");
    if (!input.value) {
        new ErrorPopupTile ("Updating failed", 
                "No update archives selected", 5)
                .show ();
    } else {
        var request = new PostRequestWithFiles ("/watchdog/update", {}, input);
        request.send (_ => console.log);
    }
}