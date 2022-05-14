import {main} from "./main";

main.showPositions = function () {
    let xhr = new XMLHttpRequest();
    xhr.addEventListener('loadend', () => {
        if (xhr.status === 200) {
            console.log(xhr);
            main.setMarker(JSON.parse(xhr.responseText));
        } else {
            console.log(xhr);
        }
    });
    xhr.open('GET', main.listURL);
    xhr.send();
}

main.showPosition = function(geoHash) {
    let xhr = new XMLHttpRequest();
    xhr.addEventListener('loadend', () => {
        if (xhr.status === 200) {
            console.log(JSON.parse(xhr.responseText));
        } else {
            console.log(xhr);
        }
    });
    xhr.open('GET', main.showURL + geoHash);
    xhr.send();
}
