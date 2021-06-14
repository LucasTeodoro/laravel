import HttpResource from "./http-resource";
import {httpVideo} from "./index";

export interface CastMember {
    id: string;
    name: string;
    type: number;
    created_at: string;
}

const castMemberHttp = new HttpResource(httpVideo, "cast_members");

export default castMemberHttp;