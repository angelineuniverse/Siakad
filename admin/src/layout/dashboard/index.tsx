import { Component } from "react";
import { ModelRespon, ModelResponList } from "../../service/response";
import { RouterInterface } from "../../router/router_component";
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Tooltip,
  Filler,
} from "chart.js";
import { Line } from "react-chartjs-2";

import {
  mahasiswaChart,
  mahasiswaLulus,
  mahasiswaactive,
  mahasiswaactiveList,
  menunggak,
} from "./service";
import { show } from "../pengumuman/service";
import Skeleton from "../../components/skeleton/skeleton";
import Table from "../../components/table/table";
ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Tooltip,
  Filler
);
class Index extends Component {
  state: Readonly<{
    mahasiswaActiveList: ModelResponList | undefined;
    mahasiswaActive: ModelRespon | undefined;
    mahasiswaMenunggak: ModelRespon | undefined;
    pengumuman: ModelRespon | undefined;
    mahasiswaLulus: ModelRespon | undefined;
    chart: ModelRespon | undefined;
    labels: Array<number>;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      mahasiswaActive: undefined,
      mahasiswaActiveList: undefined,
      pengumuman: undefined,
      mahasiswaMenunggak: undefined,
      mahasiswaLulus: undefined,
      chart: undefined,
      labels: [2021, 2022, 2023, 2024],
    };
    this.callMahasiswaActive = this.callMahasiswaActive.bind(this);
    this.callMahasiswaActiveList = this.callMahasiswaActiveList.bind(this);
    this.callMahasiswaMenunggak = this.callMahasiswaMenunggak.bind(this);
    this.callMahasiswaLulus = this.callMahasiswaLulus.bind(this);
    this.callMahasiswaChart = this.callMahasiswaChart.bind(this);
    this.callPengumuman = this.callPengumuman.bind(this);
  }

  componentDidMount(): void {
    this.callMahasiswaMenunggak();
    this.callMahasiswaActive();
    this.callMahasiswaActiveList();
    this.callMahasiswaLulus();
    this.callMahasiswaChart();
    this.callPengumuman();
  }

  async callMahasiswaActive() {
    await mahasiswaactive().then((res) => {
      this.setState({ mahasiswaActive: res.data });
    });
  }
  async callMahasiswaChart() {
    await mahasiswaChart().then((res) => {
      this.setState({ chart: res.data });
    });
  }
  async callMahasiswaActiveList() {
    await mahasiswaactiveList().then((res) => {
      this.setState({ mahasiswaActiveList: res.data });
    });
  }
  async callPengumuman() {
    await show().then((res) => {
      this.setState({ pengumuman: res.data });
    });
  }
  async callMahasiswaMenunggak() {
    await menunggak().then((res) => {
      this.setState({ mahasiswaMenunggak: res.data });
    });
  }
  async callMahasiswaLulus() {
    await mahasiswaLulus().then((res) => {
      this.setState({ mahasiswaLulus: res.data });
    });
  }
  render() {
    return (
      <div>
        <div>
          <h6 className=" font-interbold text-lg">Dashboard</h6>
          <p className=" font-interregular text-xs">
            Menu pintas untuk anda melihat semua informasi
          </p>
        </div>
        <div className="grid grid-cols-3 gap-6 mt-8">
          <div className=" rounded-lg border shadow-lg border-gray-300 flex flex-row">
            <div className="rounded-l-lg w-2 h-full bg-blue-500"></div>
            <div className="p-4">
              <h6 className=" font-intersemibold">Mahasiswa Aktif</h6>
              <p className="text-xs">Tahun Ajaran 2024/2025</p>
              <p className="text-xs font-interbold mt-4">
                <span className="text-2xl">
                  {this.state.mahasiswaActive?.response_data.length}
                </span>{" "}
                Mahasiswa
              </p>
            </div>
          </div>
          <div className=" rounded-lg border shadow-lg border-gray-300 flex flex-row">
            <div className="rounded-l-lg w-2 h-full bg-red-500"></div>
            <div className="p-4">
              <h6 className=" font-intersemibold">Mahasiswa Menunggak</h6>
              <p className="text-xs">Tahun Ajaran 2024/2025</p>
              <p className="text-xs font-interbold mt-4">
                <span className="text-2xl">
                  {this.state.mahasiswaMenunggak?.response_data.length}
                </span>{" "}
                Mahasiswa
              </p>
            </div>
          </div>
          <div className=" rounded-lg border shadow-lg border-gray-300 flex flex-row">
            <div className="rounded-l-lg w-2 h-full bg-green-500"></div>
            <div className="p-4">
              <h6 className=" font-intersemibold">Mahasiswa Lulus</h6>
              <p className="text-xs">Tahun Ajaran 2024/2025</p>
              <p className="text-xs font-interbold mt-4">
                <span className="text-2xl">
                  {this.state.mahasiswaLulus?.response_data.length}
                </span>{" "}
                Mahasiswa
              </p>
            </div>
          </div>
        </div>
        <div className="mt-8 grid grid-cols-2 gap-10">
          <div className=" col-span-1">
            <h6 className=" font-intersemibold text-md">Pengumuman Terakhir</h6>
            {!this.state.pengumuman && (
              <Skeleton type="text" className=" h-32 mt-5 w-full" />
            )}
            {this.state.pengumuman &&
              this.state.pengumuman?.response_data?.map((e: any) => {
                return (
                  <div className="rounded-lg bg-blue-800 text-white p-4 h-fit text-xs mt-3 font-interregular">
                    {e.description}
                  </div>
                );
              })}
          </div>
          <div className=" col-span-1 max-h-80">
            <h6 className=" font-intersemibold text-md mb-2">
              Mahasiswa Terdaftar
            </h6>
            <Line
              options={{
                responsive: true,
                plugins: {
                  legend: {
                    display: false,
                  },
                },
              }}
              data={{
                labels:
                  this.state.chart?.response_data.labels ?? this.state.labels,
                datasets: [
                  {
                    fill: true,
                    label: "",
                    data: this.state.chart?.response_data.dataset ?? [
                      30, 15, 88, 23, 10,
                    ],
                    borderColor: "rgb(53, 162, 235)",
                    backgroundColor: "rgba(53, 162, 235, 0.5)",
                  },
                ],
              }}
            />
          </div>
        </div>
        <div className="mt-8">
          <Table
            useCreate={false}
            useBack={false}
            useHeadline
            skeletonRow={5}
            title="Mahasiswa Aktif"
            description="Lihat semua mahasiswa yang aktif pada saat ini"
            column={this.state.mahasiswaActiveList?.response_column ?? []}
            data={this.state.mahasiswaActiveList?.response_data}
            property={this.state.mahasiswaActiveList?.property}
          />
        </div>
      </div>
    );
  }
}

export default Index;
