import { Component, ReactNode, Suspense, lazy } from "react";
import { Outlet } from "react-router-dom";
const Icon = lazy(() => import("../components/icon/icon"));
class Dashboard extends Component {
  render(): ReactNode {
    return (
      <div className="flex md:flex-col flex-col w-full max-h-screen overflow-hidden">
        <Suspense>
          <div className="flex md:flex-row gap-x-2 w-full bg-[#2C2D60] py-2.5 px-6">
            <div className="flex justify-start gap-x-2 text-white font-intermedium text-xs">
              <Icon
                icon="phone"
                width={20}
                height={20}
                color="#fff"
                className="my-auto"
              />
              <span>74857438</span>
            </div>
          </div>
        </Suspense>
        <div className="w-full overflow-y-auto md:p-6">
          <Outlet></Outlet>
        </div>
      </div>
    );
  }
}

export default Dashboard;
