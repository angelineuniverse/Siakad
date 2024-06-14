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
                path: 'pengumuman',
                children: [
                    {
                        index: true,
                        path: '',
                        async lazy() {
                            let Pengumuman = await import('../layout/pengumuman/index');
                            return { Component: Pengumuman.default}
                        },
                    },
                    {
                        path: 'create/:id?',
                        async lazy() {
                            let Pengumuman = await import('../layout/pengumuman/create');
                            return { Component: Pengumuman.default}
                        },
                    },
                ]
            },
            {
                path: 'master',
                children: [
                    {
                        path: 'member',
                        children: [
                            {
                                index: true,
                                path: '',
                                async lazy() {
                                    let Member = await import('../layout/master/member/index');
                                    return { Component: Member.default}
                                },
                            },
                            {
                                path: 'create',
                                async lazy() {
                                    let Member = await import('../layout/master/member/create');
                                    return { Component: Member.default}
                                },
                            }
                        ]
                    },
                    {
                        path: 'dosen',
                        children: [
                            {
                                index: true,
                                path: '',
                                async lazy() {
                                    let Dosen = await import('../layout/master/dosen/index');
                                    return { Component: Dosen.default}
                                },
                            },
                            {
                                path: 'create/:id?',
                                async lazy() {
                                    let Dosen = await import('../layout/master/dosen/create');
                                    return { Component: Dosen.default}
                                },
                            },
                        ]
                    },
                    {
                        path: 'mata-kuliah',
                        children: [
                            {
                                index: true,
                                path: '',
                                async lazy() {
                                    let MataKuliah = await import('../layout/master/mata kuliah/index');
                                    return { Component: MataKuliah.default}
                                },
                            },
                            {
                                path: 'create/:id?',
                                async lazy() {
                                    let MataKuliah = await import('../layout/master/mata kuliah/create');
                                    return { Component: MataKuliah.default}
                                },
                            },
                        ]
                    },
                    {
                        path: 'mahasiswa',
                        children: [
                            {
                                index: true,
                                path: '',
                                async lazy() {
                                    let Mahasiswa = await import('../layout/master/mahasiswa/periode');
                                    return { Component: Mahasiswa.default}
                                },
                            },
                            {
                                path: 'detail/:periodeId?',
                                async lazy() {
                                    let Mahasiswa = await import('../layout/master/mahasiswa/index');
                                    return { Component: Mahasiswa.default}
                                },
                            },
                            {
                                path: ':periodeId?/create/:id?',
                                async lazy() {
                                    let Mahasiswa = await import('../layout/master/mahasiswa/create');
                                    return { Component: Mahasiswa.default}
                                },
                            },
                        ]
                    }
                ]
            },
            {
                path: "akademik",
                children: [
                    {
                        path: 'krs',
                        children: [
                            {
                                index: true,
                                path: '',
                                async lazy() {
                                    let Periode = await import('../layout/akademik/krs/periode');
                                    return { Component: Periode.default}
                                },
                            },
                            {
                                path: 'detail/:periodeId?',
                                async lazy() {
                                    let Krs = await import('../layout/akademik/krs/index');
                                    return { Component: Krs.default}
                                },
                            },
                            {
                                path: ':periodeId?/create',
                                async lazy() {
                                    let Krs = await import('../layout/akademik/krs/create');
                                    return { Component: Krs.default}
                                },
                            },
                            {
                                path: ':periodeId?/update/:id/:mahasiswaId?',
                                async lazy() {
                                    let Krs = await import('../layout/akademik/krs/update');
                                    return { Component: Krs.default}
                                },
                            },
                        ]
                    },
                ]
            },
            {
                path: "finance",
                children: [
                    {
                        index: true,
                        path: '',
                        async lazy() {
                            let Periode = await import('../layout/finance/periode');
                            return { Component: Periode.default}
                        },
                    },
                    {
                        path: 'detail/:periodeId?',
                        async lazy() {
                            let Krs = await import('../layout/finance/index');
                            return { Component: Krs.default}
                        },
                    },
                    {
                        path: ':periodeId?/update/:id/:mahasiswaId?',
                        async lazy() {
                            let Krs = await import('../layout/finance/update');
                            return { Component: Krs.default}
                        },
                    },
                ]
            }
        ]
    }
]);
export default router;