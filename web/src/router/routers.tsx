import { createBrowserRouter } from "react-router-dom";
const router = createBrowserRouter([
  {
    path: "/",
    async lazy() {
      let App = await import("../layout/App.tsx");
      return { Component: App.default };
    },
    async loader() {
      let Guard = await import("./router_guard.tsx");
      return Guard.authNotExist();
    },
    // children: [
    //   {
    //     path: "",
    //     index: true,
    //     async lazy() {
    //       let Dashboard = await import("../layout/dashboard/index.tsx");
    //       return { Component: Dashboard.default };
    //     },
    //   },
    //   {
    //     path: "manage/",
    //     children: [
    //       {
    //         path: "account/",
    //         async lazy() {
    //           let Index = await import("../layout/manage/account/index.tsx");
    //           return { Component: Index.default };
    //         },
    //         children: [
    //           {
    //             path: "",
    //             index: true,
    //             async lazy() {
    //               let Show = await import("../layout/manage/account/show.tsx");
    //               return { Component: Show.default };
    //             },
    //           },
    //         ],
    //       },
    //     ],
    //   },
    // ],
  },
  {
    path: "/login",
    async lazy() {
      let Login = await import("../layout/auth/login.tsx");
      return { Component: Login.default };
    },
    async loader() {
      let Guard = await import("./router_guard.tsx");
      return Guard.authExist();
    },
  },
]);
export default router;
