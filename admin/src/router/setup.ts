import { createBrowserRouter } from "react-router-dom";

const router = createBrowserRouter([
    {
        path: '/auth',
        async lazy() {
            let Auth = await import('../layout/auth/auth');
            return { Component: Auth.default}
        },
        async loader() {
            let guard = await import('./guard');
            return guard.authExist();
        },
    },
    {
        path: '/',
        async lazy() {
            let Index = await import('../layout/index');
            return { Component: Index.default}
        },
        async loader() {
            let guard = await import('./guard');
            return guard.authNotExist();
        },
        children: [
            {
                index: true,
                async lazy() {
                    let Dashboard = await import('../layout/dashboard/index');
                    return { Component: Dashboard.default}
                },
            },
            {
                path: 'master',
                children: [
                    {
                        path: 'anggota',
                        async lazy() {
                            let Anggota = await import('../layout/master/anggota/index');
                            return { Component: Anggota.default}
                        },
                    }
                ]
            },
        ]
    }
]);
export default router;