import { ErrorPopupTile } from "./popup";


export class Request {

    constructor (
        protected method : string,
        protected url    : string,
        protected data   : {}
    ) {};

    public send <T> (handler : (response : T) => void) : void {
        this.sendRequest (this.createRequest (handler));
    }

    protected sendRequest (descriptor : XMLHttpRequest) {
        descriptor.setRequestHeader ("Content-Type", "application/json");
        descriptor.send (JSON.stringify (this.data));
    }

    protected createRequest <T> (handler : (response : T) => void) : XMLHttpRequest {
        var descriptor = new XMLHttpRequest ();
        descriptor.open (this.method, this.url, true);
        
        descriptor.onreadystatechange = function () {
            if (descriptor.readyState != 4) { return; }
            
            if (descriptor.status >= 200 && descriptor.status < 300) {
                handler (<T> JSON.parse (descriptor.responseText));
            } else {
                var message = "Some error occured during connection to server (code " 
                            + descriptor.status + ")";
                new ErrorPopupTile ("Request failed", message, 5).show ();
            }
        }

        return descriptor;
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

export class PostRequestWithFiles extends PostRequest {

    constructor (
        protected url  : string,
        protected data : {},
        protected input: HTMLInputElement
    ) { super (url, data); };

    protected form = new FormData ();

    protected sendRequest (descriptor : XMLHttpRequest) {
        descriptor.send (this.form);
    }

    protected createRequest <T> (handler : (response : T) => void) : XMLHttpRequest {
        var descriptor = super.createRequest (handler);
        for (var i = 0; i < this.input.files.length; i++) {
            var file = this.input.files [i];
            this.form.append (file.name, file, file.name);
        }

        Object.keys (this.data).forEach (key => {
            this.form.append (key, this.data [key]);
        });

        return descriptor;
    }

}

// Network objects (DTOs)

export class AuthVerdict {
    public verdict : boolean;
    public message : string;
}