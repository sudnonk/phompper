import Phompper from "./phompper";
import PhompperUtil from "./phompper_util";

document.addEventListener('DOMContentLoaded', async () => {
    M.AutoInit();
    console.log("materialize init");
    const phompper = new Phompper(
        PhompperUtil.getInputValue("token"),
        PhompperUtil.getHrefValue("submitURL"),
        PhompperUtil.getHrefValue("listURL"),
        PhompperUtil.getHrefValue("showURL"),
        PhompperUtil.getHrefValue("deleteURL")
    );
    await phompper.updateCurrentLocation();
    await phompper.showPositions();
    document.getElementById('register')?.addEventListener('click', () => {
        phompper.submitFormData();
    });
    document.getElementById('reload')?.addEventListener('click', async () => {
        await phompper.showPositions();
    });
    document.getElementById('location')?.addEventListener('click', () => {
        phompper.updateCurrentLocation();
    })

    //電柱タブに入力された値を電信柱タブにコピーする
    //電信柱も電柱も連番のことが多いので、同じ値が入っていると便利
    interface T_input_pair {
        [key: string]: string;

        shisen_denshin: string;
        denshin_type: string;
        number_denshin: string;
        shisen_denchu: string;
        denchu_type: string;
        number_denchu: string;
    }
    const input_pair: T_input_pair = {
        "shisen_denshin": "shisen_denchu",
        "denshin_type": "denchu_type",
        "number_denshin": "number_denchu",
        "shisen_denchu": "shisen_denshin",
        "denchu_type": "denshin_type",
        "number_denchu": "number_denshin"
    };
    Object.keys(input_pair).forEach(key => {
        document.getElementById(key)?.addEventListener('change', () => {
            phompper.copyInputForDenchuAndDenshin(key,input_pair[key]);
        })
    });

});
