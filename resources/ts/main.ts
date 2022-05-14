export interface mainInterface {
    token: string,
    registerURL: string,
    listURL: string,
    showURL: string,

    initMap(): void;

    gm_authFailure(): void;

    setMarker(data: JSON): void;

    showPositions(): void;

    showPosition(geoHash: string): void;

    setParams(): void;

    getInputValue(elemId: string): string;
}

export let main: mainInterface = {
    token: "",
    registerURL: "",
    listURL: "",
    showURL: "",
    initMap() {
    },
    gm_authFailure() {
    },
    setMarker(data: JSON) {
    },
    showPosition(geoHash: string) {
    },
    showPositions() {
    },
    setParams() {
    },
    getInputValue(elemId: string): string {
        return "";
    }
};

document.addEventListener('DOMContentLoaded', () => {
    M.AutoInit();
    console.log("materialize init");
    main.setParams();
});
window.addEventListener('load', () => {
    main.initMap();
    console.log("map init");
    main.showPositions();
});

/**
 * elemIdのHTMLInputElementのValueを返す。elemIdのHTMLInputElementが見つからなければ空文字列
 * @param elemId
 * @return HTMLInputElementのValueの値
 */
main.getInputValue = function (elemId: string): string {
    const elem = document.getElementById(elemId);
    if (elem !== null
        && ((elem instanceof HTMLInputElement)
            || (elem instanceof HTMLSelectElement)
            || (elem instanceof HTMLTextAreaElement))) {
        return elem.value;
    }
    return "";
}

function getHrefValue(elemId: string): string {
    const elem = document.getElementById(elemId);
    if (elem !== null && elem instanceof HTMLAnchorElement) {
        return elem.href;
    }
    return "";
}


main.setParams = function () {
    main.token = main.getInputValue("token");
    main.registerURL = getHrefValue("registerURL");
    main.listURL = getHrefValue("listURL");
    main.showURL = getHrefValue("showURL");
}
