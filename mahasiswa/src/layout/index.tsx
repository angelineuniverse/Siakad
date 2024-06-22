import { Component, ReactNode, Suspense, lazy } from "react";
import { NavLink, Outlet } from "react-router-dom";
import Logo from "../assets/image/Logo Fatahilah.png";
import Avatar from "../assets/image/Avatar.png";
import Dropdown from "../components/dropdown/Dropdown";
import { logout } from "./auth/service";
import { removeCookie } from "typescript-cookie";

const Icon = lazy(() => import("../components/icon/icon"));
class Dashboard extends Component {
  async callLogout() {
    await logout().then((res) => {
      console.log(res);
      removeCookie("token");
      window.location.href = "/";
    });
  }
  render(): ReactNode {
    return (
      <div className="flex md:flex-col flex-col w-full max-h-screen overflow-hidden">
        <Suspense>
          <div className="flex md:flex-row gap-x-6 w-full bg-[#2C2D60] py-2.5 px-6">
            <div className="flex justify-start gap-x-1 text-white font-intermedium text-xs h-fit">
              <Icon icon="phone" width={19} height={19} color="#fff" />
              <p className="my-auto">(0225) 4398905</p>
            </div>
            <div className="flex justify-start gap-x-1 text-white font-intermedium text-xs h-fit">
              <Icon icon="marker" width={19} height={19} color="#fff" />
              <p className="my-auto">
                Kramatwatu, Kabupaten Serang, Provinsi Banten
              </p>
            </div>
            <div className="flex justify-start gap-x-1 text-white font-intermedium text-xs h-fit">
              <Icon icon="mail" width={19} height={19} color="#fff" />
              <p className="my-auto">sttfatahillahcilegon@yahoo.co.id</p>
            </div>
          </div>
        </Suspense>
        <Suspense>
          <div className="flex md:flex-row gap-x-2 w-full bg-[#CCE2FC] py-2.5 px-6">
            <div className="flex justify-start items-center gap-x-3">
              <img src={Logo} alt="logo" />
              <p className=" font-interbold text-xs max-w-36">
                Sekolah Tinggi <br /> Teknik Fatahilah
              </p>
            </div>
            <div className="md:flex hidden md:flex-row gap-x-8 w-fit items-center md:ml-auto">
              <NavLink
                className="text-sm font-interbold text-gray-900 cursor-pointer"
                to={""}
              >
                Beranda
              </NavLink>
              <NavLink
                className="text-sm font-interbold text-gray-900 cursor-pointer"
                to={"pengumuman"}
              >
                Pengumuman
              </NavLink>
              <NavLink
                className="text-sm font-interbold text-gray-900 cursor-pointer"
                to={"krs"}
              >
                KRS
              </NavLink>
              <NavLink
                className="text-sm font-interbold text-gray-900 cursor-pointer"
                to={"studi"}
              >
                Hasil Studi
              </NavLink>
              <Dropdown
                trigger={<img src={Avatar} alt="avatar" className="h-8" />}
                children={
                  <div className="p-5 flex gap-y-3 flex-col">
                    <NavLink
                      className="text-sm w-full font-interbold text-gray-900 cursor-pointer"
                      to={"profile"}
                    >
                      Data Mahasiswa
                    </NavLink>
                    <NavLink
                      className="text-sm w-full font-interbold text-gray-900 cursor-pointer"
                      to={"finance"}
                    >
                      Riwayat Keuangan
                    </NavLink>
                    <div
                      onClick={() => this.callLogout()}
                      className="cursor-pointer font-interbold justify-start gap-x-2 text-red-500"
                    >
                      <p>Keluar</p>
                    </div>
                  </div>
                }
                direction="bottom-right"
              ></Dropdown>
            </div>
          </div>
        </Suspense>
        <div className="w-full overflow-y-auto md:p-6 bg-white">
          <Outlet></Outlet>
        </div>
      </div>
    );
  }
}

export default Dashboard;
