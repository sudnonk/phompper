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
});
