import HttpResource from "./http-resource";
import {httpVideo} from "./index";

export interface Category {
    id: string;
    name: string;
    created_at: string;
}

const categoryHttp = new HttpResource(httpVideo, "categories");

export default categoryHttp;