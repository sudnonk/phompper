export default class PhompperUtil {
    /**
     * HTMLの#infoのinnerTextをmsgに置き換える
     * @param msg
     */
    static showInfo(msg: string): void {
        const infoDOM = document.getElementById('info');
        if (infoDOM === null) {
            console.error("elementId 'info' not found.");
            return;
        }
        infoDOM.innerText = msg;
    }

    /**
     * HTMLの#elemIdを取得し、それがvalueプロパティのあるelementであればそのvalueプロパティの中身を返し、それ以外は空文字列を返す
     * @param elemId
     * @return string
     */
    static getInputValue(elemId: string): string {
        const elem = document.getElementById(elemId);
        if (elem !== null
            && ((elem instanceof HTMLInputElement)
                || (elem instanceof HTMLSelectElement)
                || (elem instanceof HTMLTextAreaElement))) {
            return elem.value;
        } else {
            console.error("elementId '" + elemId + "' not found or not valid element.");
            return "";
        }
    }

    /**
     * HTMLの#elemIdを取得し、それがhrefプロパティのあるelementであればそのhrefプロパティの中身を返し、それ以外は空文字列を返す
     * @param elemId
     * @return string
     */
    static getHrefValue(elemId: string): string {
        const elem = document.getElementById(elemId);
        if (elem !== null && elem instanceof HTMLAnchorElement) {
            return elem.href;
        } else {
            console.error("elementId '" + elemId + "' not found or not valid element.");
            return "";
        }
    }
}
