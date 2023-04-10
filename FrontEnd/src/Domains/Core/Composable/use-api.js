import axios from "axios";

export default function useApi() {

    const api = axios.create({
        baseURL: process.env.VUE_APP_API_BASE_URL,
        headers: {
            'accept': 'application/json',
            "Content-Type": 'application/json'
        }
    });


    const get = async (url) => {
        const { data } = await api.get(url);

        return data;
    }

    const post = async (url, body) => {
        const { data } = await api.post(url, body);

        return data;
    }

    const put = async (url, body) => {
        const { data } = await api.put(url, body);

        return data;
    }

    const destroy = async (url) => {
        const { data } = await api.delete(url);

        return data;
    }

    return {
        get,
        post,
        put,
        destroy
    }
}
