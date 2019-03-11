import { addPopupTile, addShortErrorPopupTile } from "./popup";
import { element, dataElement, inputElement } from "./common";
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
                addShortErrorPopupTile ("Wrong file selected", 
                      "Only archives .zip and .gzip allowed");
                status.innerHTML = "file not selected";
                input.value = "";
            }
        }
    }
}

export function uploadWatchdogUpdate () : void {
    var input = inputElement ("watchdogUpdateFile");
    if (!input.value) {
        addShortErrorPopupTile ("Updating failed", 
                    "No update archives selected");
    } else {
        var request = new PostRequestWithFiles ("/watchdog/update", {}, input);
        request.send (_ => console.log);
    }
}