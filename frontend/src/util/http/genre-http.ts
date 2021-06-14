import HttpResource from "./http-resource";
import {httpVideo} from "./index";
import {Category} from "./category-http";

export interface Genre {
    id: string;
    name: string;
    categories: Category[];
    created_at: string;
}

const genreHttp = new HttpResource(httpVideo, "genres");

export default genreHttp;