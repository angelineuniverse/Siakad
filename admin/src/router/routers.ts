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
            let Dashboard = await import('../layout/dashboard');
            return { Component: Dashboard.default}
        }
    }
]);
export default router;