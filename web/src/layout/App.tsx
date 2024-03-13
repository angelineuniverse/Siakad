import { Link, Outlet } from "react-router-dom";
import React, { Component } from "react";
import Icon from "../components/icon/icon.tsx";
import Logo from "../assets/image/logo.png";

class App extends Component {
  render(): React.ReactNode {
    return (
      <div>
        <div className="w-full md:gap-x-9 md:h-auto md:py-3 text-white font-intersemibold text-xs bg-[#2C2D60] flex items-center md:px-28 justify-start">
          <div className="flex items-center gap-x-1.5">
            <Icon icon="phone" width={22} height={22} color="#fff" />
            <span>(0225) 4398905</span>
          </div>
          <div className="flex items-center gap-x-1.5">
            <Icon icon="marker" width={22} height={22} color="#fff" />
            <span>Kramatwatu, Kabupaten Serang, Provinsi Banten</span>
          </div>
          <div className="flex items-center gap-x-1.5">
            <Icon icon="mail" width={22} height={22} color="#fff" />
            <span>sttfatahillahcilegon@yahoo.co.id</span>
          </div>
        </div>
        <div className="w-full md:h-auto md:py-3 text-white font-intersemibold text-xs bg-[#CCE2FC] flex items-center md:px-28 justify-start md:gap-x-20">
          <div className="mr-auto flex gap-x-3 items-center">
            <img src={Logo} alt="logo" className="h-9 w-9" />
            <p className=" font-interbold text-xs leading-4 text-black">
              Sekolah Tinggi <br /> Teknik Fatahilah
            </p>
          </div>
          <div className="flex justify-start gap-x-10 font-interbold text-black text-sm">
            <Link to="">Beranda</Link>
            <Link to="">Jadwal</Link>
            <Link to="">Akademik</Link>
            <Link to="">Tingkat Akhir</Link>
            <Link to="">Hasil Studi</Link>
          </div>
          <div className="rounded-full h-9 w-9 bg-gray-600"></div>
        </div>
        <Outlet />
      </div>
    );
  }
}
export default App;
