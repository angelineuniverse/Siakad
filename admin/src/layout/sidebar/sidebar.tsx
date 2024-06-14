import { Component, ReactNode, Suspense, lazy } from "react";
import { NavLink } from "react-router-dom";
import Logo from "../../assets/image/Logo Fatahilah.png";
import ArrowRight from "../../assets/image/arrow_right.svg";
import ArrowBottom from "../../assets/image/arrow_bottom.svg";
import Logout from "../../assets/image/logout.svg";
import Avatar from "../../assets/image/Avatar.png";
import { show, logout } from "./service";
import { me } from "../auth/service";
const Skeleton = lazy(() => import("../../components/skeleton/skeleton"));

class Sidebar extends Component {
  state: Readonly<{
    menu: Array<any> | undefined;
    me: any;
  }>;

  constructor(props: any) {
    super(props);
    this.state = {
      menu: undefined,
      me: undefined,
    };
    this.index = this.index.bind(this);
  }

  componentDidMount(): void {
    this.index();
  }

  async index() {
    await me().then((res) => {
      this.setState({
        me: res.data?.response_data,
      });
    });
    await show().then((res) => {
      this.setState({
        menu: res.data?.response_data,
      });
    });
  }

  async logout() {
    await logout().then((res) => {});
  }
  render(): ReactNode {
    return (
      <div className="block p-3 overflow-auto overflow-y-auto">
        <div className="flex justify-start items-center gap-x-3 md:mb-8">
          <img src={Logo} alt="logo" />
          <p className=" font-interbold text-xs max-w-36">
            Sekolah Tinggi <br /> Teknik Fatahilah
          </p>
        </div>
        <div className="text-center mx-auto md:mb-10">
          <img
            src={Avatar}
            alt="Avatar"
            width={100}
            height={100}
            className="text-center mx-auto"
          />
          <Suspense>
            {!this.state.me && (
              <div className="mt-3 mx-3">
                <Skeleton type="text" />
              </div>
            )}
          </Suspense>
          <h2 className="mt-3 font-interbold">{this.state.me?.name}</h2>
          <p className=" font-intermedium text-xs">{this.state.me?.role}</p>
        </div>
        <ul className="gap-y-4 flex flex-col">
          {this.state.menu?.map((menu, index) => {
            return (
              <div key={menu.id}>
                <li className="text-base flex gap-x-2.5 items-center font-interbold text-gray-700">
                  <img width={20} height={20} src={menu.icon_url} alt="icon" />
                  {menu.child?.length < 1 && (
                    <NavLink to={menu.url}>{menu.title}</NavLink>
                  )}
                  {menu.child?.length > 0 && (
                    <>
                      <button
                        onClick={() => {
                          let menus = this.state.menu as Array<any>;
                          menus[index].dropdown = !menu.dropdown;
                          this.setState((prevState) => ({
                            menu: menus,
                          }));
                        }}
                        className="text-base font-interbold text-gray-700 cursor-pointer"
                      >
                        {menu.title}
                      </button>
                      {!menu.dropdown && (
                        <img
                          width={20}
                          height={20}
                          className="ml-auto"
                          src={ArrowRight}
                          alt="arrow"
                        />
                      )}
                      {menu.dropdown && (
                        <img
                          width={20}
                          height={20}
                          className="ml-auto"
                          src={ArrowBottom}
                          alt="arrow"
                        />
                      )}
                    </>
                  )}
                </li>
                <ul className="gap-y-0.5 flex flex-col">
                  {menu.dropdown &&
                    menu.child.map((child: any) => {
                      return (
                        <li
                          key={child.title}
                          className="text-base ml-3 mt-4 flex gap-x-2.5 items-center font-interbold text-gray-700"
                        >
                          <img
                            width={20}
                            height={20}
                            src={child.icon_url}
                            alt="icon"
                          />
                          <NavLink to={child.url}>{child.title}</NavLink>
                        </li>
                      );
                    })}
                </ul>
              </div>
            );
          })}
          {this.state.menu && (
            <li className="text-base flex gap-x-3 items-center font-interbold text-gray-700">
              <img width={17} height={17} src={Logout} alt="icon" />
              <button
                onClick={this.logout}
                className="text-base font-interbold text-red-700 cursor-pointer"
              >
                Logout
              </button>
            </li>
          )}
        </ul>
      </div>
    );
  }
}

export default Sidebar;
