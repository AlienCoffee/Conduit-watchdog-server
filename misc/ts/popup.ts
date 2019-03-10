import { element } from './common'

export function addPopupTile (title : string, message : string, 
        style : string = "", timeout : number) : void {
    var tile = document.createElement ("div");
    tile.classList.add ("popup-tile", style);

    var _title = document.createElement ("h4");
    _title.classList.add ("popup-title");
    tile.appendChild (_title);
    _title.innerHTML = title;

    var _message = document.createElement ("p");
    _message.classList.add ("popup-message");
    tile.appendChild (_message);
    _message.innerHTML = message;

    element ("popup").prepend (tile);
    setTimeout (function () {
        element ("popup").removeChild (tile);
    }, timeout * 1000);
}

export function addErrorPopupTile (title : string, message : string, timeout : number) {
    addPopupTile (title, message, "popup-error", timeout);
}

export function addShortErrorPopupTile (title : string, message : string) {
    addErrorPopupTile (title, message, 5);
}