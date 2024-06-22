import axios from "axios";
import { getCookie } from "typescript-cookie";
import { redirect } from "react-router-dom";
import { notification } from "../components/notification/notificationService";
const client = axios.create({
    baseURL: 'http://localhost:8081/api/v1/',
    headers: {
        Authorization: `Bearer ${getCookie('token')}`
    }
})
client.interceptors.request.use((config) => {
    const token = getCookie("token");
    if (token) config.headers["Authorization"] = `Bearer ${token}`;
    return config;
});

client.interceptors.response.use(
    async function (response) {
        const res = await response.data;
        if (res?.response_notifikasi) {
            notification.show(
                {
                    key: 'notif',
                    position: 'top-right',
                    theme: res.response_notifikasi?.theme,
                    body: res.response_notifikasi?.body,
                    title: res.response_notifikasi?.title,
                    duration: 5000
                }
            )
        }

        return response;
    },
    function (error) {
        if (error?.response?.status === 401) { return redirect('/login'); }
        if (error?.response?.status === 400) {
            notification.show(
                {
                    key: 'notif',
                    position: 'top-right',
                    theme: 'error',
                    title: "Validasi Gagal",
                    body: error?.response?.data?.message,
                    duration: 5000
                }
            )
        } else {
            notification.show(
                {
                    key: 'notif',
                    position: 'top-right',
                    theme: 'error',
                    title: error?.response?.data?.response_notifikasi?.title,
                    body: error?.response?.data?.response_notifikasi?.body,
                    duration: 5000
                }
            )
        }
        return Promise.reject(new Error(error));
    }
)


export default client;