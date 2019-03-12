import { element } from './common'

export class PopupTile {

    protected tile    : HTMLDivElement;
    protected title   : HTMLHeadingElement;
    protected message : HTMLParagraphElement;

    constructor (
        protected timeout : number,
        title   : string,
        message : string,
        style   : string = "popup-info"
    ) {
        this.tile = document.createElement ("div");
        this.tile.classList.add ("popup-tile", style);
    
        this.title = document.createElement ("h4");
        this.title.classList.add ("popup-title");
        this.tile.appendChild (this.title);
        this.title.innerHTML = title;
    
        this.message = document.createElement ("p");
        this.message.classList.add ("popup-message");
        this.tile.appendChild (this.message);
        this.message.innerHTML = message;
    }

    public changeTitle (title : string) : PopupTile {
        this.title.innerHTML = title;
        return this;
    }
    
    public changeMessage (message : string) : PopupTile {
        this.message.innerHTML = message;
        return this;
    }

    protected static styles = ["popup-info", "popup-error"];

    public changeStyle (style : string) : PopupTile {
        PopupTile.styles.forEach (st => this.tile.classList.remove (st));
        this.tile.classList.add (style);

        return this;
    }

    public switchToInfo () : PopupTile { 
        this.changeStyle (PopupTile.styles [1]); 
        return this;
    }

    public switchToError () : PopupTile { 
        this.changeStyle (PopupTile.styles [2]); 
        return this;
    }

    public show () {
        element ("popup").prepend (this.tile);
        this.destructAfter (this.timeout);
    }

    public destructAfter (timeout : number) {
        if (timeout > 0 && timeout < 300) { // from 1 to 300 seconds
            setTimeout (this.delete, timeout * 1000);
        }
    }

    public delete () {
        element ("popup").removeChild (this.tile);
    }

}

export class ErrorPopupTile extends PopupTile {

    constructor (
        title   : string,
        message : string,
        timeout : number
    ) { 
        var style : string = PopupTile.styles [2];
        super (timeout, title, message, style); 
    }

}