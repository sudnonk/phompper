interface Window {
    registerForm: {
        "token": string,
        "url": string,
    };
}

declare var window: Window & typeof globalThis;

document.getElementById("register")?.addEventListener('click', () => {
    if (window.registerForm.token === undefined) {
        return false;
    }
    if (window.registerForm.url === undefined) {
        return false;
    }
    const input = getFormData();
    if (input === null) {
        return false;
    }
    input.append("_token", window.registerForm.token);

    let xhr = new XMLHttpRequest();
    xhr.addEventListener('loadend', () => {
        if (xhr.status === 200) {

        } else {

        }
    });
    xhr.open('POST', window.registerForm.url);
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
    data.append("line", getInputValue("shisen_denshin").concat(getInputValue("denshin_type")));
    data.append("number", getInputValue("number_denshin"));
    data.append("note", getInputValue("note_denshin"));

    return data;
}

function collectDenchuData(): FormData {
    let data = new FormData();
    data.append("type", "電柱");
    data.append("line", getInputValue("shisen_denchu").concat(getInputValue("denchu_type")));
    data.append("number", getInputValue("number_denchu"));
    data.append("note", getInputValue("note_denchu"));

    return data;
}

function collectBuildingData(): FormData {
    let data = new FormData();
    data.append("type", "通信ビル");
    data.append("name", getInputValue("name_building").concat(getInputValue("building_type")));
    data.append("note", getInputValue("note_building"));

    return data;
}

function collectOtherData(): FormData {
    let data = new FormData();
    data.append("type", "その他");
    data.append("note", getInputValue("note_other"));

    return data;
}

/**
 * elemIdのHTMLInputElementのValueを返す。elemIdのHTMLInputElementが見つからなければ空文字列
 * @param elemId
 * @return HTMLInputElementのValueの値
 */
function getInputValue(elemId: string): string {
    const elem = document.getElementById(elemId);
    if (elem !== null
        && ((elem instanceof HTMLInputElement)
            || (elem instanceof HTMLSelectElement)
            || (elem instanceof HTMLTextAreaElement))) {
        return elem.value;
    }
    return "";
}
