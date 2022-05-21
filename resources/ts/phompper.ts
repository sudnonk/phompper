import PhompperMap from "./phompper_map";
import LatLng = google.maps.LatLng;
import Marker = google.maps.Marker;
import PhompperForm from "./phompper_form";

//定義はPHP側のapp\Http\Resources
interface PositionListObj {
    geoHash: string,
    latitude: number,
    longitude: number,
    type: string
}

interface PositionObj {
    latitude: number,
    longitude: number,
    type: string,
    note: string | null,
    lineNumber: string | null,
    lineName: string | null,
    buildingName: string | null
}

export default class Phompper {
    map: PhompperMap = new PhompperMap();

    constructor(
        private token: string,
        private registerURL: string,
        private listURL: string,
        private showURL: string
    ) {
    }


    /**
     * そのgeoHashのPositionの詳細情報をデータベースから取得する。
     * @param geoHash
     */
    async getPosition(geoHash: string): Promise<PositionObj | null> {
        return fetch(this.showURL + "/" + geoHash)
            .then(
                success => {
                    return success.json();
                },
                fails => {
                    console.warn(fails);
                    return null
                }
            )
            .catch(error => {
                console.warn(error);
                return null;
            });
    }

    async showPosition(geoHash: string): Promise<void> {
        let posData = await this.getPosition(geoHash);
        if (posData === null) {
            console.warn("failed to get data of " + geoHash);
            return;
        }
        //todo: モーダル処理
    }

    async getPositions(): Promise<Array<PositionListObj> | null> {
        return fetch(this.listURL)
            .then(
                success => {
                    return success.json();
                },
                fails => {
                    console.warn(fails);
                    return null
                }
            )
            .catch(error => {
                console.warn(error);
                return null;
            });
    }

    /**
     * データベースにすでに登録されている地点にマーカーを設置する
     */
    async showPositions(): Promise<void> {
        const data = await this.getPositions();
        if (data === null) {
            console.warn("failed to get positions");
            return;
        }

        let markers = [];
        data.forEach(datum => {
            let pos = new LatLng({lat: datum.latitude, lng: datum.longitude})
            let icon;
            switch (datum.type) {
                case "DENSHIN":
                    icon = "https://maps.google.com/mapfiles/ms/icons/red-dot.png"
                    break;
                case "DENCHU":
                    icon = "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    break;
                case "BUILDING":
                    icon = "https://maps.google.com/mapfiles/ms/icons/green-dot.png"
                    break;
                case "OTHER":
                    icon = "https://maps.google.com/mapfiles/ms/icons/yellow-dot.png"
                    break;
            }

            let marker = new Marker({
                position: pos,
                map: this.map.getGMap(),
                icon: icon,
                title: datum.geoHash,
                optimized: false,
            });
            marker.addListener('click', async () => {
                await this.showPosition(datum.geoHash)
            });
            console.log(datum);
            console.log(this.map.getGMap());
            console.log(marker);


            markers.push(marker);
        })
    }

    async submit(data: FormData): Promise<JSON | null> {
        return fetch(this.registerURL, {
            method: "POST",
            body: data
        }).then(
            success => {
                return success.json();
            },
            fails => {
                console.warn(fails);
                return null
            }
        )
            .catch(error => {
                console.warn(error);
                return null;
            });
    }

    async submitFormData(): Promise<boolean> {
        const input = PhompperForm.getFormData();
        if (input === null) {
            return false;
        }
        input.append("_token", this.token);
        console.log(...input.entries());

        const submitted = await this.submit(input);
        if (submitted === null) {
            console.warn("failed to register.");
            return false;
        }
        console.log(submitted);

        await this.showPositions();
        return false;
    }
}
