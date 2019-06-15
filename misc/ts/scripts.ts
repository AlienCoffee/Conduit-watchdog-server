import { element, clearChildren, dataElement, inputElement } from "./common";
import { GetRequest, ScriptsBatch, Verdict, Script, PostRequestWithFiles } from "./network";
import { ErrorPopupTile, PopupTile } from "./popup";


export function initScripts () : void {
    setInterval (() => scriptsUpdateHandler (), 60_000);
    scriptsUpdateHandler (); // Initial update

    dataElement ("scriptFile").onchange = function (_ : Event) : void {
        var input = inputElement ("scriptFile");
        var parent = input.parentElement;
        var status = parent.getElementsByClassName ("file-upload-selection") [0];
		//var textarea = parent.parentElement;
		//var filename = textarea.getElementsByClassName ("form-textarea") [0];
		
        if   (!input.value) { status.innerHTML = "file not selected"; } 
        else {
            var file = input.value.replace (new RegExp ("\\\\", "g"), "/")
                     . split ("/").reduce ((_, v) => v, "");
            var extension = file.split (".").reduce ((_, v) => v, "")
                          . toLowerCase ();

            if (["cmd", "sh"].indexOf (extension) !== -1) {
                status.innerHTML = " " + file + " ";
            } else {
                new ErrorPopupTile ("Wrong file selected", 
                        "Only .sh or .cmd files are allowed", 5)
                        .show ();
                status.innerHTML = "file not selected";
                input.value = "";
            }
        }
    }
	dataElement("filename").onchange = function (_ : Event) : void {
               
		var filename = <HTMLTextAreaElement> element("filename");

		if (!filename.value){
            filename.value = "";
		} else {
			
		}
	}
}

var scripts : Script [] = [];

function scriptsUpdateHandler () {
    var preloader = element ("scriptsPreloader");
    preloader.style.opacity = "1";

    new GetRequest ("/watchdog/scripts", {})
    . send ((response : ScriptsBatch) => {
        preloader.style.opacity = "0";
        if (!response.verdict) {
            new ErrorPopupTile ("Scripts retrieving failed", 
                                response.message, 10).show ();
            return;
        }

        scripts = response.scripts;
        refreshScripts (scripts);
    }, _ => {
        preloader.style.opacity = "0";
        
        var list = element ("listOfScripts");
        clearChildren (list);
        
        return false; // still notify about error
    });
}

function refreshScripts (scripts : Script []) {
    var list = element ("listOfScripts");
    clearChildren (list);

    scripts.forEach (script => {
        var line = document.createElement ("li");
        //script.platform
        line.classList.add (script.platform + "-script");
        line.classList.add ("console-list-item");
        list.appendChild (line);

        var name = document.createElement ("span");
        name.classList.add ("script-item-name");
        name.innerHTML = script.name;
        line.appendChild (name);

        // Run button
        var button = document.createElement ("button");
        button.id = [script.platform, script.name].join (";");
        button.classList.add ("script-item-button");
        line.appendChild (button);

        var buttonIcon = document.createElement ("img");
        buttonIcon.src = "/misc/img/icons/play-arrow.svg";
        button.appendChild (buttonIcon);

        // Delete button
        var button = document.createElement ("button");
        button.id = [script.platform, script.name].join (";");
        button.classList.add ("script-item-button");
        line.appendChild (button);

        var buttonIcon = document.createElement ("img");
        buttonIcon.src = "/misc/img/icons/trash-bin.svg";
        button.appendChild (buttonIcon);
    });
}

export function uploadScript () {
    var input = inputElement ("scriptFile");
	var filename = <HTMLTextAreaElement> element("filename");
    if (!input.value) {
        new ErrorPopupTile ("Uploading failed", 
                "None files selected", 5)
                .show ();
    } else {
        var request = new PostRequestWithFiles ("/watchdog/scripts", {filename: filename.value}, input);
        var popup = new PopupTile (10, "Uploading scripts", "Processing upload on server");
        popup.show ();

        request.send ((response : Verdict) => {
            if (!response.verdict) {
                popup.changeMessage (response.message)
                     .switchToError ()
                     .destructAfter (10);
            } else { location.reload (); }
        });
    }
}