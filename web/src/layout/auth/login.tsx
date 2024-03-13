import React, { Component, ReactNode, lazy } from "react";
import Logo from "../../assets/image/logo.png";
const Input = lazy(() => import("../../components/input/input.tsx"));
const Button = lazy(() => import("../../components/button/button.tsx"));
class Login extends Component {
  render(): ReactNode {
    return (
      <div className="bg-[#F0F0F0] flex justify-center items-center h-screen max-h-screen">
        <div className="w-4/12 bg-white rounded-md border border-gray-300 shadow-sm p-10">
          <div className="flex gap-x-3 items-center">
            <img src={Logo} alt="logo" className="h-10 w-10" />
            <p className=" font-interbold text-sm leading-5">
              Sekolah Tinggi <br /> Teknik Fatahilah
            </p>
          </div>
          <div className="mt-12">
            <h1 className=" font-interbold text-2xl">Portal Akademik</h1>
            <p className=" text-xs font-interregular">
              Masuk menggunakan Nomor Induk Mahasiswa dan Password anda
            </p>
            <Input className="mt-8" label="Nomor Induk Mahasiswa" />
            <Input className="mt-5" label="Password" type="password" />
            <Button
              className="mt-10"
              title="Masuk"
              theme="primary"
              size="medium"
              width="full"
            />
          </div>
        </div>
      </div>
    );
  }
}

export default Login;
