import LatLng = google.maps.LatLng;
import PhompperUtil from "./phompper_util";

export default class PhompperForm {

    /**
     * HTMLのinputタグ #latitudeと#longitudeに、latlngのlatitudeとlongitudeを値として入力する
     * @param latlng
     */
    static setLatLng(latlng: LatLng): void {
        const latDOM = document.getElementById("latitude");
        const lngDOM = document.getElementById("longitude");
        if (latDOM === null || !(latDOM instanceof HTMLInputElement)) {
            return;
        }
        if (lngDOM === null || !(lngDOM instanceof HTMLInputElement)) {
            return;
        }

        latDOM.value = latlng.lat().toString();
        lngDOM.value = latlng.lng().toString();
    }

    /**
     * フォームに入力されたデータを集める
     * @return FormData|null #position-typeの値が想定していない時、null
     */
    static getFormData(): FormData | null {
        const tabElem = document.getElementById("position-type");
        if (tabElem === null) {
            return null;
        }
        const tabIndex = M.Tabs.getInstance(tabElem)?.index;

        let data: FormData;
        switch (tabIndex) {
            case 0:
                data = PhompperForm.collectDenshinData();
                break;
            case 1:
                data = PhompperForm.collectDenchuData()
                break;
            case 2:
                data = PhompperForm.collectBuildingData()
                break;
            case 3:
                data = PhompperForm.collectOtherData()
                break;
            default:
                return null;
        }

        data.append('lat', PhompperUtil.getInputValue("latitude"));
        data.append('long', PhompperUtil.getInputValue("longitude"));
        return data;
    }

    private static collectDenshinData(): FormData {
        let data = new FormData();
        data.append("type", "電信柱");
        data.append("line", PhompperUtil.getInputValue("shisen_denshin").concat(PhompperUtil.getInputValue("denshin_type")));
        data.append("number", PhompperUtil.getInputValue("number_denshin"));
        data.append("note", PhompperUtil.getInputValue("note_denshin"));
        return data;
    }

    private static collectDenchuData(): FormData {
        let data = new FormData();
        data.append("type", "電柱");
        data.append("line", PhompperUtil.getInputValue("shisen_denchu").concat(PhompperUtil.getInputValue("denchu_type")));
        data.append("number", PhompperUtil.getInputValue("number_denchu"));
        data.append("note", PhompperUtil.getInputValue("note_denchu"));

        return data;
    }

    private static collectBuildingData(): FormData {
        let data = new FormData();
        data.append("type", "通信ビル");
        data.append("name", PhompperUtil.getInputValue("name_building").concat(PhompperUtil.getInputValue("building_type")));
        data.append("note", PhompperUtil.getInputValue("note_building"));

        return data;
    }

    private static collectOtherData(): FormData {
        let data = new FormData();
        data.append("type", "その他");
        data.append("note", PhompperUtil.getInputValue("note_other"));

        return data;
    }
}
