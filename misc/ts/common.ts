

export function element (id : string) : HTMLElement {
    return document.getElementById (id);
}

export function dataElement (id : string) : HTMLDataElement {
    return <HTMLDataElement> element (id);
}

export function inputElement (id : string) : HTMLInputElement {
    return <HTMLInputElement> element (id);
}