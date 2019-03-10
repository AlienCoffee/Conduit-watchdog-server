import { addShortErrorPopupTile } from "./popup";


export class Request {

    constructor (
        protected method : string,
        protected url    : string,
        protected data   : {}
    ) {};

    public send <T> (handler : (response : T) => void) : void {
        var descriptor = new XMLHttpRequest ();
        descriptor.open (this.method, this.url, true);

        descriptor.setRequestHeader ("Content-Type", "application/json");
        descriptor.send (JSON.stringify (this.data));

        descriptor.onreadystatechange = function () {
            if (descriptor.readyState != 4) { return; }
            
            if (descriptor.status >= 200 && descriptor.status < 300) {
                handler (<T> JSON.parse (descriptor.responseText));
            } else {
                var message = "Some error occured during connection to server (code " 
                            + descriptor.status + ")";
                addShortErrorPopupTile ("Request failed", message);
            }
        }
    }

}

export class PostRequest extends Request {

    constructor (
        protected url  : string,
        protected data : {}
    ) { super ("POST", url, data); };

}

export class GetRequest extends Request {

    constructor (
        protected url  : string,
        protected data : {}
    ) { super ("GET", url, data); };

}

// Network objects (DTOs)

export class AuthVerdict {
    public verdict : boolean;
    public message : string;
}