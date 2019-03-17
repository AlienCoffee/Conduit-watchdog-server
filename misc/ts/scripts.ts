import { element, clearChildren } from "./common";
import { GetRequest, ScriptsBatch, Verdict, Script } from "./network";
import { ErrorPopupTile } from "./popup";


export function initScripts () : void {
    setInterval (() => scriptsUpdateHandler (), 60_000);
    scriptsUpdateHandler (); // Initial update
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
        line.classList.add (script.platform + "-script");
        line.classList.add ("console-list-item");
        list.appendChild (line);

        var name = document.createElement ("span");
        name.classList.add ("script-item-name");
        name.innerHTML = script.name;
        line.appendChild (name);
    });
}

export function uploadScript () {
    
}