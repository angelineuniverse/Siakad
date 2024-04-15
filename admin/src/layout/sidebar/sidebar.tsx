import { Component, ReactNode } from "react";
import { Link } from "react-router-dom";
import Logo from "../../assets/image/Logo Fatahilah.png";
import ArrowRight from "../../assets/image/arrow_right.svg";
import ArrowBottom from "../../assets/image/arrow_bottom.svg";
import Logout from "../../assets/image/logout.svg";
import { show, logout } from "./service";
class Sidebar extends Component {
  state: Readonly<{
    menu: Array<any> | undefined;
  }>;

  constructor(props: any) {
    super(props);
    this.state = {
      menu: undefined,
    };
    this.index = this.index.bind(this);
  }

  componentDidMount(): void {
    this.index();
  }

  async index() {
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
      <div className="block p-3">
        <div className="flex justify-start items-center gap-x-3 md:mb-16">
          <img src={Logo} alt="logo" />
          <p className=" font-interbold text-xs max-w-36">
            Sekolah Tinggi <br /> Teknik Fatahilah
          </p>
        </div>
        <ul className="gap-y-4 flex flex-col">
          {this.state.menu?.map((menu, index) => {
            return (
              <div key={menu.id}>
                <li className="text-base flex gap-x-2.5 items-center font-interbold text-gray-700">
                  <img width={20} height={20} src={menu.icon_url} alt="icon" />
                  {menu.child?.length < 1 && (
                    <Link to={menu.url}>{menu.title}</Link>
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
                          <Link to={child.url}>{child.title}</Link>
                        </li>
                      );
                    })}
                </ul>
              </div>
            );
          })}
          {this.state.menu && (
            <li className="text-base flex gap-x-2.5 items-center font-interbold text-gray-700">
              <img width={20} height={20} src={Logout} alt="icon" />
              <button
                onClick={this.logout}
                className="text-base font-interbold text-gray-700 cursor-pointer"
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
