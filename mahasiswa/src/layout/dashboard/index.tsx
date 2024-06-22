import { Component, Suspense } from "react";
import Sevi from "../../assets/image/sevi.png";
import Icon from "../../components/icon/icon";
import { jadwal, me, tagihan, ipk } from "./service";
import { RouterInterface } from "../../router/router_component";
import { ModelRespon, ModelResponList } from "../../service/response";
import Table from "../../components/table/table";
import Skeleton from "../../components/skeleton/skeleton";
import moment from "moment";
class Index extends Component {
  state: Readonly<{
    profile: undefined | ModelRespon;
    ipkfinal: undefined | ModelRespon;
    bayaran: undefined | ModelRespon;
    jadwal: undefined | ModelResponList;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      profile: undefined,
      bayaran: undefined,
      ipkfinal: undefined,
      jadwal: undefined,
    };
  }

  componentDidMount(): void {
    this.callMe();
    this.callJadwal();
    this.callBayaran();
    this.callIpk();
  }

  async callMe() {
    await me().then((res) => {
      this.setState({
        profile: res.data,
      });
    });
  }
  async callIpk() {
    await ipk().then((res) => {
      this.setState({
        ipkfinal: res.data,
      });
    });
  }
  async callBayaran() {
    await tagihan().then((res) => {
      this.setState({
        bayaran: res.data,
      });
    });
  }
  async callJadwal() {
    await jadwal().then((res) => {
      this.setState({
        jadwal: res.data,
      });
    });
  }
  render() {
    return (
      <div className="grid md:grid-cols-3 grid-cols-1 gap-8">
        <div className="col-span-full bg-white border border-gray-300 w-full px-8 grid grid-cols-1 md:grid-cols-2 gap-0">
          <div className=" flex md:flex-row flex-col border-r border-gray-400">
            <img src={Sevi} alt="sevi" className="h-[132px] mt-4" />
            <div className="-ml-3 py-4 max-w-80">
              {!this.state.profile && (
                <Suspense>
                  <Skeleton type="custom" className=" w-full h-4" />
                </Suspense>
              )}
              {this.state.profile && (
                <p className=" font-interbold text-base">
                  Hallo, {this.state.profile?.response_data?.name}
                </p>
              )}
              <p className="text-xs font-interregular">
                Saat ini anda berada di{" "}
                {
                  this.state.profile?.response_data?.semester_active?.semester
                    ?.title
                }{" "}
                dengan hasil IPS & IPK sebagai berikut, Untuk melihat lebih
                detail perkuliahan silahkan
                <span className="cursor-pointer font-intersemibold text-blue-700 text-xs">
                  {" "}
                  klik disini.
                </span>
              </p>
            </div>
          </div>
          <div className=" flex md:flex-row flex-col">
            <div className="flex items-center flex-row gap-10 md:py-4 md:px-7">
              <div>
                <p className=" font-interbold text-xs text-gray-500">IPK</p>
                {!this.state.ipkfinal && (
                  <Skeleton type="custom" className=" w-14 h-5 mt-2" />
                )}
                {this.state.ipkfinal && (
                  <p className="text-4xl font-interbold text-black">
                    {this.state.ipkfinal?.response_data}
                  </p>
                )}
              </div>
              <div className="text-center">
                <div className="rounded-full w-8 h-8 mx-auto flex justify-center items-center bg-red-200">
                  <Icon
                    icon="arrow_down"
                    width={15}
                    height={15}
                    color="#D71E1E"
                  />
                </div>
                <p className="text-xl font-interbold mt-2 text-red-500">4.00</p>
              </div>
            </div>
          </div>
        </div>
        <div className=" col-span-2 p-5 bg-white border border-gray-300">
          <div className="flex justify-start items-center">
            <div>
              <h6 className=" font-interbold text-base text-blue-950">
                Jadwal Kuliah
              </h6>
              <p className="text-xs font-intermedium">
                Lihat semua jadwal perkuliahan anda saat ini
              </p>
            </div>
            <p className="text-xs font-intermedium mb-auto ml-auto">
              {moment().format("DD MMMM YYYY")}
            </p>
          </div>
          {!this.state.jadwal?.response_data && (
            <p className="text-center font-interregular mt-10 text-xs">
              Belum ada jadwal untuk semester saat ini, segera periksa dan
              mengisi KRS
            </p>
          )}
          {this.state.jadwal?.response_data && (
            <Table
              useCreate={false}
              useBack={false}
              useHeadline={false}
              skeletonRow={5}
              column={this.state.jadwal?.response_column ?? []}
              data={this.state.jadwal?.response_data}
              property={this.state.jadwal?.property}
            />
          )}
        </div>
        <div className=" col-span-1">
          <h6 className=" font-interbold text-sm text-blue-950">
            Total Tagihan Perkuliahan
          </h6>
          <div className=" bg-white mt-2 p-5 border border-gray-300">
            <h6 className=" font-interbold text-2xl text-blue-950">
              {!this.state.bayaran && <Skeleton type="text" className="h-3" />}
              {this.state.bayaran?.response_data.sisa.toLocaleString("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
              })}
            </h6>
            <p className="text-gray-600 font-intersemibold text-xs">
              Anda sudah membayar{" "}
              {this.state.bayaran?.response_data.payment.toLocaleString(
                "id-ID",
                {
                  style: "currency",
                  currency: "IDR",
                  minimumFractionDigits: 0,
                }
              )}{" "}
              dari{" "}
              {this.state.bayaran?.response_data.default.toLocaleString(
                "id-ID",
                {
                  style: "currency",
                  currency: "IDR",
                  minimumFractionDigits: 0,
                }
              )}
            </p>
            <p className="text-black mt-1 font-intersemibold text-xs">
              {this.state.bayaran?.response_data?.semester?.semester?.title}
            </p>
          </div>
        </div>
      </div>
    );
  }
}

export default Index;
