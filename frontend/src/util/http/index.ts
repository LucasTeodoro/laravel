import axios, {AxiosRequestConfig} from 'axios';

const httpVideoConfig: AxiosRequestConfig = {
    baseURL: process.env.REACT_APP_MICRO_VIDEO_URL
}

export const httpVideo = axios.create(httpVideoConfig);