import { Component, ReactNode, lazy } from "react";
import { Outlet } from "react-router-dom";
const Sizebar = lazy(() => import("./sidebar/sidebar"));
class Dashboard extends Component {
  render(): ReactNode {
    return (
      <div className="flex md:flex-row flex-col w-full max-h-screen overflow-hidden">
        <div className="md:w-2/12 h-screen border-r border-gray-300 overflow-auto pb-14">
          <Sizebar />
        </div>
        <div className="w-full overflow-y-auto md:p-6">
          <Outlet></Outlet>
        </div>
      </div>
    );
  }
}

export default Dashboard;
