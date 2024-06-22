import { Component, ReactNode } from "react";
import logo from "../../assets/image/Logo Fatahilah.png";
import Input from "../../components/input/input";
import Button from "../../components/button/button";
import { login } from "../auth/service";
import { setCookie } from "typescript-cookie";
import { RouterInterface, withRouter } from "../../router/router_component";
class Auth extends Component<RouterInterface> {
  state: Readonly<{
    nim: undefined;
    password: undefined;
    loading: false;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      loading: false,
      nim: undefined,
      password: undefined,
    };
    this.callLogin = this.callLogin.bind(this);
  }
  async callLogin() {
    this.setState({ loading: true });
    await login({
      nim: this.state.nim,
      password: this.state.password,
    })
      .then((res) => {
        if (res.data?.response_data)
          setCookie("token", res.data?.response_data?.token);
        // this.setState({ loading: false });
        return this.props.navigate("/");
      })
      .catch((err) => {
        this.setState({ loading: false });
      });
  }
  render(): ReactNode {
    return (
      <div className="flex justify-center max-h-screen h-screen items-center w-full bg-latar md:px-0 px-3">
        <div className="w-full md:w-[443px] rounded-lg border px-8 pt-8 pb-12 shadow bg-white border-gray-300">
          <div className="flex justify-start gap-x-3 items-center">
            <img src={logo} alt="logo" width={30} height={30} />
            <p className=" font-interbold text-xs max-w-36">
              Sekolah Tinggi <br /> Teknik Fatahilah
            </p>
          </div>
          <h1 className="text-2xl font-interbold mt-12">Portal Akademik</h1>
          <p className="text-sm font-interregular">
            Masuk menggunakan nim dan Password anda
          </p>
          <div className="mt-8 gap-y-4 flex flex-col">
            <Input
              label="Nomor Induk Mahasiswa"
              type="text"
              size="medium"
              onValueChange={(value: string) => this.setState({ nim: value })}
            />
            <Input
              label="Password"
              type="password"
              size="medium"
              onValueChange={(value: string) =>
                this.setState({ password: value })
              }
            />
            <Button
              title="Masuk"
              theme="primary"
              size="medium"
              isLoading={this.state.loading}
              width="full"
              className="mt-6"
              onClick={this.callLogin}
            />
          </div>
        </div>
      </div>
    );
  }
}

export default withRouter(Auth);
