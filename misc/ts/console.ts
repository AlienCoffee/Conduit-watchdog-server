import { ErrorPopupTile, PopupTile } from "./popup";
import { dataElement, inputElement } from "./common";
import { PostRequestWithFiles, AuthVerdict } from "./network";


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
        var popup = new PopupTile (10, "Updating watchdog", "Processing update on server");
        popup.show ();

        request.send ((response : AuthVerdict) => {
            if (!response.verdict) {
                popup.changeMessage (response.message)
                     .switchToError ()
                     .destructAfter (10);
            } else { location.reload (); }
        });
    }
}