import { Component, Suspense, lazy } from "react";
import {
  selectedMatakuliah,
  nilai,
  updateNilai,
  updateStatus,
} from "./service";
import { ModelRespon, formInputsObject } from "../../../service/response";
import { withRouter, RouterInterface } from "../../../router/router_component";
import clsx from "clsx";
import ArrowLeft from "../../../assets/image/arrow_left.svg";
import Skeleton from "../../../components/skeleton/skeleton";
import Checkbox from "../../../components/checkbox/checkbox";
const Button = lazy(() => import("../../../components/button/button"));

class Studi extends Component<RouterInterface> {
  state: Readonly<{
    index: ModelRespon | undefined;
    nilai: ModelRespon | undefined;
    loading: boolean;
    checkbox: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      index: undefined,
      nilai: undefined,
      checkbox: false,
      loading: false,
    };
    this.callIndex = this.callIndex.bind(this);
    this.callNilai = this.callNilai.bind(this);
  }

  componentDidMount(): void {
    this.callIndex();
    this.callNilai();
  }
  async callIndex() {
    await selectedMatakuliah(
      this.props.params?.periodeId,
      this.props.params?.mahasiswaId
    ).then((res) => {
      this.setState({
        index: res.data,
      });
    });
  }
  async callNilai() {
    await nilai().then((res) => {
      this.setState({
        nilai: res.data,
      });
    });
  }
  async callSaveNilai(params: any) {
    const form = formInputsObject(params);
    await updateNilai(params.id, form).then((res) => {
      return this.callNilai();
    });
  }
  async callValidasi() {
    await updateStatus(this.state.index?.response_data?.id).then((res) => {
      return this.props.navigate(-1);
    });
  }
  render() {
    return (
      <div>
        <div className="flex gap-x-5 mb-12">
          <div
            onClick={() => this.props.navigate(-1)}
            aria-hidden="true"
            className="my-auto cursor-pointer"
          >
            <img src={ArrowLeft} alt="ArrowLeft" />
          </div>
          <div>
            <h6 className=" font-interbold text-lg">Berikan Nilai KRS</h6>
            <p className=" font-interregular text-xs">
              Tambahkan nilai pada KRS mahasiswa
            </p>
          </div>
        </div>
        <p className="mb-3 font-interbold text-xs text-red-600">
          {this.state.index?.response_data?.semester_mahasiswa?.semester?.title}{" "}
          {
            this.state.index?.response_data?.semester_mahasiswa
              ?.semester_periode?.title
          }
        </p>
        <div className="overflow-hidden border border-gray-300">
          <table className="w-full h-auto min-w-full divide-y divide-gray-200">
            <thead className="bg-gradient-to-b from-gray-100 to-gray-100">
              <tr className="text-xs font-intermedium">
                <th className="py-3 text-center text-xs px-4">Code</th>
                <th className="py-3 text-start text-xs px-4">Mata Kuliah</th>
                <th className="py-3 text-start text-xs px-4">Dosen</th>
                <th className="py-3 text-center text-xs px-4">Bobot SKS</th>
                <th className="py-3 text-center text-xs px-4">Nilai</th>
                <th className="py-3 text-center text-xs px-4">Action</th>
              </tr>
            </thead>
            <tbody>
              {this.state.index?.response_data?.matakuliah?.map(
                (item: any, index: number) => {
                  return (
                    <tr
                      key={item.id}
                      className="text-xs text-start font-interregular border-b border-gray-200"
                    >
                      <td className="text-center font-interbold">
                        {item.detail_matakuliah.code}
                      </td>
                      <td className="px-4 py-2 text-justify text-xsm font-interbold">
                        {item.detail_matakuliah.title}
                      </td>
                      <td className="px-4 text-justify">
                        {item.detail_matakuliah.dosen?.name}
                      </td>
                      <td className="px-4 text-center">
                        {item.detail_matakuliah.bobot_sks}
                      </td>
                      <td className="py-2 text-center">
                        <select
                          onChange={(event) => {
                            item.m_nilai_tabs_id = event.target.value;
                          }}
                          className={clsx(
                            "border border-gray-400/70 font-interregular rounded-lg block w-full appearance-none",
                            "focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400",
                            "bg-no-repeat relative text-sm px-2 py-1 placeholder:text-sm"
                          )}
                        >
                          {item.nilai && (
                            <option selected value={item.nilai.id}>
                              {item.nilai.title}
                            </option>
                          )}
                          {!item.nilai && (
                            <option selected value={item.nilai}>
                              Pilih Nilai
                            </option>
                          )}
                          {this.state.nilai?.response_data.map((a: any) => {
                            return (
                              <option
                                className=" py-2 px-2 font-intersemibold text-sm"
                                key={a.id}
                                value={a.id}
                              >
                                {a.title}
                              </option>
                            );
                          })}
                        </select>
                      </td>
                      <td className="text-center py-2.5 flex justify-center items-center">
                        <Suspense>
                          <Button
                            title="Simpan"
                            theme="primary"
                            size="extrasmall"
                            width="block"
                            onClick={() => {
                              this.callSaveNilai(item);
                            }}
                          />
                        </Suspense>
                      </td>
                    </tr>
                  );
                }
              )}
            </tbody>
          </table>
          {!this.state.index?.response_data && (
            <div className="mt-2 px-2">
              <Skeleton type="custom" className="h-7 w-full" />
              <Skeleton type="custom" className="h-7 w-full" />
              <Skeleton type="custom" className="h-7 w-full" />
              <Skeleton type="custom" className="h-7 w-full" />
            </div>
          )}
        </div>
        {this.state.index?.response_data?.m_status_tabs_id !== 8 && (
          <Suspense>
            <Checkbox
              className="my-4"
              label="Memvalidasi KRS berarti mahasiswa dianggap telah selesai melalui perkuliahan dalam semester tersebut"
              onValueChange={(value: boolean) => {
                this.setState({
                  checkbox: value,
                });
              }}
            />
            <Button
              isDisable={this.state.checkbox}
              title="Validasi KRS"
              theme="primary"
              size="medium"
              isLoading={this.state.loading}
              width="block"
              onClick={() => this.callValidasi()}
            />
          </Suspense>
        )}
      </div>
    );
  }
}

export default withRouter(Studi);
