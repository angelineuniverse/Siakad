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
                    return { Component: Dashboard.default }
                },
            },
            {
                path: 'studi',
                async lazy() {
                    let Anggota = await import('../layout/studi/studi');
                    return { Component: Anggota.default}
                },
            },
            {
                path: "pengumuman",
                async lazy() {
                    let Pengumuman = await import('../layout/pengumuman/pengumuman');
                    return { Component: Pengumuman.default}
                },
            },
            {
                path: "profile",
                async lazy() {
                    let Profile = await import('../layout/profile/profile');
                    return { Component: Profile.default}
                },
            },
            {
                path: "krs",
                async lazy() {
                    let Krs = await import('../layout/krs/krs');
                    return { Component: Krs.default}
                },
            },
            {
                path: "finance",
                async lazy() {
                    let Finance = await import('../layout/finance/finance');
                    return { Component: Finance.default}
                },
            },
        ]
    },
    
]);
export default router;