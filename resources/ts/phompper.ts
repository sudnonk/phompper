import PhompperMap from "./phompper_map";
import LatLng = google.maps.LatLng;
import Marker = google.maps.Marker;
import PhompperForm from "./phompper_form";
import InfoWindow = google.maps.InfoWindow;
import PhompperUtil from "./phompper_util";

//定義はPHP側のapp\Http\Resources
interface PositionListObj {
    geoHash: string,
    latitude: number,
    longitude: number,
    type: string
}

interface PositionObj {
    geoHash: string,
    latitude: number,
    longitude: number,
    type: string,
    note: string | null,
    lineNumber: string | null,
    lineName: string | null,
    buildingName: string | null,
    imageURLs: string[]
}

export default class Phompper {
    map: PhompperMap = new PhompperMap();
    infoWindow: InfoWindow | null = null;
    markers: Marker[] = [];

    constructor(
        private token: string,
        private registerURL: string,
        private listURL: string,
        private showURL: string,
        private deleteURL: string
    ) {
    }

    async updateCurrentLocation(): Promise<void> {
        await this.map.updateCurrentPosition();
    }

    /**
     * そのgeoHashのPositionの詳細情報をデータベースから取得する。
     * @param geoHash
     */
    async getPosition(geoHash: string): Promise<PositionObj | null> {
        return fetch(this.showURL + "/" + geoHash)
            .then(
                response => {
                    if (response.ok) {
                        return response.json();
                    } else {
                        console.warn(response);
                        return null;
                    }
                }
            )
            .catch(error => {
                console.warn(error);
                return null;
            });
    }

    showInfoWindow(marker: Marker, posData: PositionObj): void {
        let content_by_type = "";
        switch (posData.type) {
            case "電信柱":
            case "電柱":
                content_by_type += '<li>' + posData.lineName + ' ' + posData.lineNumber + '</li>';
                break;
            case "通信ビル":
                content_by_type += '<li>' + posData.buildingName + '</li>';
                break;
        }

        let content_img = "";
        posData.imageURLs.forEach(url => {
            content_img += "<img src='" + url + "' alt='image' class='position-img'>";
        });

        const content =
            '<div>' +
            '<ul>' +
            '<li>' + posData.latitude + 'N ' + posData.longitude + 'E</li>' +
            '<li>' + posData.type + '</li>' +
            content_by_type +
            '<li>' + posData.note + '</li>' +
            '</ul>' +
            content_img +
            '<button id="infowindow-delete">削除</button>' +
            '</div>'

        //もしいま開いているinfoWindowがあれば、それは閉じる
        if (this.infoWindow !== null) {
            this.infoWindow.close();
        }
        this.infoWindow = new InfoWindow({
            content: content
        });
        this.infoWindow.open({
            anchor: marker,
            map: this.map.getGMap(),
            shouldFocus: false
        });
        this.infoWindow.addListener('visible', () => {
            console.log(document.getElementById("infowindow-delete"));
            document.getElementById('infowindow-delete')?.addEventListener("click", async () => {
                await this.deletePosition(posData.geoHash);
            })
        })
    }

    async showPosition(marker: Marker, geoHash: string): Promise<void> {
        let posData = await this.getPosition(geoHash);
        if (posData === null) {
            console.warn("failed to get data of " + geoHash);
            return;
        }
        this.showInfoWindow(marker, posData);
    }

    async getPositions(): Promise<Array<PositionListObj> | null> {
        return fetch(this.listURL)
            .then(
                response => {
                    //ステータスコードが200ならGET成功
                    if (response.ok) {
                        return response.json();
                    } else {
                        console.warn(response);
                        return null;
                    }
                }
            )
            .catch(error => {
                console.warn(error);
                return null;
            });
    }

    closeAllMarkers(): void {
        this.markers.forEach(marker => {
            marker.setMap(null);
        });
        this.markers = [];
    }

    /**
     * データベースにすでに登録されている地点にマーカーを設置する
     */
    async showPositions(): Promise<void> {
        PhompperUtil.showInfo("地点一覧を取得しています。");
        const data = await this.getPositions();
        if (data === null) {
            PhompperUtil.showInfo("地点一覧の取得に失敗しました。");
            console.warn("failed to get positions");
            return;
        }
        PhompperUtil.showInfo("地点一覧を取得しました。地図上に描画しています。");

        this.closeAllMarkers();
        data.forEach(datum => {
            let pos = new LatLng({lat: datum.latitude, lng: datum.longitude})
            let icon;
            switch (datum.type) {
                case "電信柱":
                    icon = "https://maps.google.com/mapfiles/ms/icons/red-dot.png"
                    break;
                case "電柱":
                    icon = "https://maps.google.com/mapfiles/ms/icons/blue-dot.png"
                    break;
                case "通信ビル":
                    icon = "https://maps.google.com/mapfiles/ms/icons/green-dot.png"
                    break;
                case "その他":
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
                await this.showPosition(marker, datum.geoHash)
            });
            this.markers?.push(marker);
        })
        PhompperUtil.showInfo("地点一覧を表示しました。");
    }

    async submit(data: FormData): Promise<JSON | null> {
        return fetch(this.registerURL, {
            method: "POST",
            body: data
        }).then(
            response => {
                if (response.ok) {
                    return response.json();
                } else {
                    console.warn(response);
                    return null;
                }
            }
        ).catch(error => {
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

        PhompperUtil.showInfo("データを送信しています。");
        const submitted = await this.submit(input);

        if (submitted === null) {
            PhompperUtil.showInfo("データの送信に失敗しました。");
            console.warn("failed to register.");
            return false;
        }
        console.log(submitted);
        PhompperUtil.showInfo("データを送信しました。");

        await this.showPositions();
        return false;
    }

    async submitDeletePosition(geoHash: string): Promise<boolean> {
        return fetch(this.deleteURL + "/" + geoHash, {
            method: "DELETE"
        })
            .then(
                response => {
                    if (response.ok) {
                        return true;
                    } else {
                        console.warn(response);
                        return false;
                    }
                }
            )
            .catch(
                error => {
                    console.warn(error);
                    return false
                }
            )
    }

    async deletePosition(geoHash: string): Promise<void> {
        if (confirm("本当にこの地点を削除しますか？")) {
            const result = await this.submitDeletePosition(geoHash);
            console.log(result);
            if (result) {
                PhompperUtil.showInfo("地点を削除しました。");
                await this.showPositions();
            } else {
                PhompperUtil.showInfo("地点の削除に失敗しました。");
            }
        } else {
            return;
        }
    }

    copyInputForDenchuAndDenshin(from: string, to: string): void {
        PhompperForm.copyInputForDenchuAndDenshin(from, to);
    }
}
