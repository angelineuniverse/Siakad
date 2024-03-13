import axios from "axios";
import { notification } from "../components/notification/notificationService.tsx";
import { getCookie,removeCookie } from 'typescript-cookie';
import { redirect } from "react-router-dom";
const client = axios.create({
    baseURL: "http://127.0.0.1:8000/api",
    headers: {
        Authorization: `Bearer ${getCookie("token")}`,
    },
});

client.interceptors.request.use((config) => {
    const token = getCookie("token");
    if (token) config.headers["Authorization"] = `Bearer ${token}`;
    return config;
});
  
client.interceptors.response.use(
    async function (response) {
        const res = await response.data;
        if (res?.response_notification) {
            notification.show({
                position: 'top-right',
                theme: res?.response_notification?.type,
                title: res?.response_notification?.title,
                body: res?.response_notification?.description,
                duration: 5000,
              });
        }
        return response;
    },
    (error) => {
        console.log(error, "error response");
        // kalau token expired atau unautorized
        if (error?.response?.status === 408 || error?.response?.status === 401) {
            removeCookie('token') 
            return redirect("/login");
        }
        notification.show({
            position: 'top-right',
            theme: 'error',
            title: error?.response?.data?.response_notification?.title,
            body: error?.response?.data?.response_notification?.description,
            duration: 5000,
        });
        return Promise.reject(error);
    }
);

export default client;
