

export function element (id : string) : HTMLElement {
    return document.getElementById (id);
}

export function dataElement (id : string) : HTMLDataElement {
    return <HTMLDataElement> element (id);
}

export function inputElement (id : string) : HTMLInputElement {
    return <HTMLInputElement> element (id);
}

export function clearChildren (element : HTMLElement) : void {
    var iterations = element.children.length;
    for (var i = 0; i < iterations; i++) {
        element.children [0].remove ();
    }
}