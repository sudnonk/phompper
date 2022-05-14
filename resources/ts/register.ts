import {main} from "./main";

document.getElementById("register")?.addEventListener('click', () => {
    if (main.token === undefined) {
        return false;
    }
    if (main.registerURL === undefined) {
        return false;
    }
    const input = getFormData();
    if (input === null) {
        return false;
    }
    input.append("_token", main.token);
console.log(input);
    let xhr = new XMLHttpRequest();
    xhr.addEventListener('loadend', () => {
        if (xhr.status === 200) {
            console.log(JSON.parse(xhr.responseText));
        } else {
            console.log(xhr);
        }
    });
    xhr.open('POST', main.registerURL);
    xhr.send(input);
});

function getFormData(): FormData | null {
    const tabElem = document.getElementById("position-type");
    if (tabElem === null) {
        return null;
    }
    const tabIndex = M.Tabs.getInstance(tabElem)?.index;

    let input: FormData;
    switch (tabIndex) {
        case 0:
            input = collectDenshinData();
            break;
        case 1:
            input = collectDenchuData()
            break;
        case 2:
            input = collectBuildingData()
            break;
        case 3:
            input = collectOtherData()
            break;
        default:
            return null;
    }

    return input;
}

function collectDenshinData(): FormData {
    let data = new FormData();
    data.append("type", "電信柱");
    data.append("line", main.getInputValue("shisen_denshin").concat(main.getInputValue("denshin_type")));
    data.append("number", main.getInputValue("number_denshin"));
    data.append("note", main.getInputValue("note_denshin"));

    return data;
}

function collectDenchuData(): FormData {
    let data = new FormData();
    data.append("type", "電柱");
    data.append("line", main.getInputValue("shisen_denchu").concat(main.getInputValue("denchu_type")));
    data.append("number", main.getInputValue("number_denchu"));
    data.append("note", main.getInputValue("note_denchu"));

    return data;
}

function collectBuildingData(): FormData {
    let data = new FormData();
    data.append("type", "通信ビル");
    data.append("name", main.getInputValue("name_building").concat(main.getInputValue("building_type")));
    data.append("note", main.getInputValue("note_building"));

    return data;
}

function collectOtherData(): FormData {
    let data = new FormData();
    data.append("type", "その他");
    data.append("note", main.getInputValue("note_other"));

    return data;
}
