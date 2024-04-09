import { createBrowserRouter } from "react-router-dom";

const router = createBrowserRouter([
    {
        path: '/auth',
        async lazy() {
            let Auth = await import('../layout/auth/auth');
            return { Component: Auth.default}
        }
    },
    {
        path: '/',
        async lazy() {
            let App = await import('../App');
            return { Component: App.default}
        }
    }
]);
export default router;