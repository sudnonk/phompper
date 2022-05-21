import Phompper from "./phompper";
import PhompperUtil from "./phompper_util";

document.addEventListener('DOMContentLoaded', async () => {
    M.AutoInit();
    console.log("materialize init");
    const phompper = new Phompper(
        PhompperUtil.getInputValue("token"),
        PhompperUtil.getHrefValue("submitURL"),
        PhompperUtil.getHrefValue("listURL"),
        PhompperUtil.getHrefValue("showURL")
    );
    await phompper.showPositions();
});
