import axios from "axios";
import { getCookie } from "typescript-cookie";
import { redirect } from "react-router-dom";
import { notification } from "./components/notification/notificationService";
const client = axios.create({
    baseURL: '',
    headers: {
        Authorization: `Bearer ${getCookie('token')}`
    }
})

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
                    duration: 3000
                }
            )
        }

        return response;
    },
    function (error) {
        if (error?.response?.status === 401) {
            return redirect('/login');
        }
        notification.show(
            {
                key: 'notif',
                position: 'top-right',
                theme: 'error',
                body: error?.response?.data?.response_notifikasi?.body,
                title: error?.response?.data?.response_notifikasi?.title,
                duration: 3000
            }
        )
        return Promise.reject(error);
    }
)


export default client;