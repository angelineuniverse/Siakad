import { Component, lazy, Suspense } from "react";
import { store, listMatakuliah } from "./service";
import { ModelRespon } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import Collapse from "../../components/collapse/collapse";

const Button = lazy(() => import("../../components/button/button"));
const Checkbox = lazy(() => import("../../components/checkbox/checkbox"));

class Create extends Component<RouterInterface> {
  state: Readonly<{
    mahasiswa: ModelRespon | undefined;
    matakuliah: undefined | ModelRespon;
    listSelected: Array<any>;
    loading: boolean;
    checkbox: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      loading: false,
      listSelected: [],
      mahasiswa: undefined,
      checkbox: false,
      matakuliah: undefined,
    };
    this.matakuliah = this.matakuliah.bind(this);
  }

  componentDidMount(): void {
    this.matakuliah();
  }
  async matakuliah() {
    await listMatakuliah().then((res) => {
      this.setState({
        matakuliah: res.data,
      });
    });
  }
  async create(matakuliah: any) {
    this.setState({ loading: true });
    await store({
      t_periode_tabs_id: this.state.matakuliah?.response_data?.periode?.id,
      matakuliah: matakuliah,
    })
      .then((res) => {
        this.setState({ loading: false });
        this.matakuliah();
        return this.props.navigate(-1);
      })
      .catch((err) => {
        this.setState({ loading: false });
      });
  }

  render() {
    return (
      <div>
        <div className="flex gap-x-5 mb-12">
          <div>
            <h1 className=" font-interbold text-lg">Tambah KRS Baru</h1>
            <p className=" font-interregular text-xs">
              Pastikan anda melengkapi informasi dibawah ini
            </p>
          </div>
        </div>
        {this.state.matakuliah?.response_data?.exist && (
          <p className="mt-8 text-center font-interregular text-sm">
            Anda sudah mengisi KRS
          </p>
        )}
        {!this.state.matakuliah?.response_data?.active && (
          <p className="mt-8 text-center font-interregular text-sm">
            Saat ini anda tidak dapat mengisi KRS karena periode ditutup
          </p>
        )}
        {this.state.matakuliah?.response_data?.active &&
          !this.state.matakuliah?.response_data?.exist && (
            <Suspense>
              <p className="mt-8 font-interbold text-lg">Pilih Mata Kuliah</p>
              <p className=" font-interregular text-xs mb-4">
                Pilih Mata Kuliah yang akan ditempuh mahasiswa
              </p>
              {this.state.matakuliah?.response_data?.matkul.map(
                (event: any) => {
                  return (
                    <Collapse key={event.id} title={event.title}>
                      <div className="overflow-hidden border border-gray-300">
                        <table className="w-full h-auto min-w-full divide-y divide-gray-200">
                          <thead className="bg-gradient-to-b from-gray-100 to-gray-100">
                            <tr className="text-xs font-intermedium">
                              <th className="py-3 text-center text-xs px-4">
                                Code
                              </th>
                              <th className="py-3 text-start text-xs px-4">
                                Mata Kuliah
                              </th>
                              <th className="py-3 text-start text-xs px-4">
                                Dosen
                              </th>
                              <th className="py-3 text-center text-xs px-4">
                                Bobot SKS
                              </th>
                              <th className="py-3 text-center text-xs px-4">
                                Jadwal
                              </th>
                              <th className="py-3 text-center text-xs px-4">
                                Action
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            {event.matakuliah?.map(
                              (item: any, index: number) => {
                                return (
                                  <tr
                                    key={item.id}
                                    className="text-xs text-start font-interregular border-b border-gray-400"
                                  >
                                    <td className="text-center">{item.code}</td>
                                    <td className="px-4 text-justify text-xsm">
                                      {item.title}
                                    </td>
                                    <td className="px-4 text-justify">
                                      {item.dosen?.name}
                                    </td>
                                    <td className="px-4 text-center">
                                      {item.bobot_sks}
                                    </td>
                                    <td className="py-2 text-center font-intermedium">
                                      <p className=" font-interbold">
                                        {item.days}
                                      </p>
                                      <p className=" text-blue-800">
                                        {item.times}
                                      </p>
                                    </td>
                                    <td className="text-center">
                                      {!item.selected && (
                                        <p
                                          aria-hidden="true"
                                          onClick={() => {
                                            item.selected = true;
                                            this.setState({
                                              listSelected: [
                                                ...this.state.listSelected,
                                                item,
                                              ],
                                              selected: true,
                                            });
                                          }}
                                          className="text-blue-800 cursor-pointer font-intersemibold"
                                        >
                                          Pilih
                                        </p>
                                      )}
                                      {item.selected && (
                                        <p
                                          aria-hidden="true"
                                          onClick={() => {
                                            item.selected = false;
                                            const deleted =
                                              this.state.listSelected!.filter(
                                                (data) => data.id !== item.id
                                              );
                                            this.setState({
                                              listSelected: deleted,
                                              selected: false,
                                            });
                                          }}
                                          className="text-red-800 cursor-pointer font-intersemibold"
                                        >
                                          Hapus
                                        </p>
                                      )}
                                    </td>
                                  </tr>
                                );
                              }
                            )}
                          </tbody>
                        </table>
                      </div>
                    </Collapse>
                  );
                }
              )}
            </Suspense>
          )}
        {this.state.matakuliah?.response_data?.active &&
          !this.state.matakuliah?.response_data?.exist && (
            <div className="pt-15 border-t border-gray-300 mt-10">
              <p className="mt-8 font-interbold text-lg">
                Mata Kuliah Yang Dipilih
              </p>
              <p className=" font-interregular text-xs mb-4">
                Mata Kuliah yang akan ditempuh mahasiswa
              </p>
              <div className="overflow-hidden border border-gray-300">
                <table className="w-full h-auto min-w-full divide-y divide-gray-200">
                  <thead className="bg-gradient-to-b from-gray-100 to-gray-100">
                    <tr className="text-xs font-intermedium">
                      <th className="py-3 text-center text-xs px-4">Code</th>
                      <th className="py-3 text-start text-xs px-4">
                        Mata Kuliah
                      </th>
                      <th className="py-3 text-start text-xs px-4">Dosen</th>
                      <th className="py-3 text-center text-xs px-4">
                        Bobot SKS
                      </th>
                      <th className="py-3 text-center text-xs px-4">Jadwal</th>
                    </tr>
                  </thead>
                  <tbody>
                    {this.state.listSelected?.map(
                      (item: any, index: number) => {
                        return (
                          <tr
                            key={item.id}
                            className="text-xs text-start font-interregular border-b border-gray-400"
                          >
                            <td className="text-center">{item.code}</td>
                            <td className="px-4 text-justify text-xsm">
                              {item.title}
                            </td>
                            <td className="px-4 text-justify">
                              {item.dosen?.name}
                            </td>
                            <td className="px-4 text-center">
                              {item.bobot_sks}
                            </td>
                            <td className="py-2 text-center font-intermedium">
                              <p className=" font-interbold">{item.days}</p>
                              <p className=" text-blue-800">{item.times}</p>
                            </td>
                          </tr>
                        );
                      }
                    )}
                  </tbody>
                </table>
                {this.state.listSelected.length < 1 && (
                  <p className="py-3 text-center font-interregular text-xs">
                    Belum ada matakuliah yang dipilih{" "}
                  </p>
                )}
              </div>
            </div>
          )}
        {this.state.matakuliah?.response_data?.active &&
          !this.state.matakuliah?.response_data?.exist && (
            <div className="my-8">
              <h5 className=" uppercase font-interbold text-sm text-red-500">
                keterangan
              </h5>
              <ul className=" list-inside list-decimal font-interregular text-xs mt-3">
                <li>Masukan informasi dengan lengkap</li>
              </ul>
            </div>
          )}
        {this.state.matakuliah?.response_data?.active &&
          !this.state.matakuliah?.response_data?.exist && (
            <Suspense>
              <Checkbox
                className="mb-4"
                label="Saya bertanggung jawab dengan informasi yang saya masukan"
                onValueChange={(value: boolean) => {
                  this.setState({
                    checkbox: value,
                  });
                }}
              />
              <Button
                isDisable={this.state.checkbox}
                title="Tambah KRS Mahasiswa"
                theme="primary"
                size="medium"
                isLoading={this.state.loading}
                width="block"
                onClick={() => this.create(this.state.listSelected)}
              />
            </Suspense>
          )}
      </div>
    );
  }
}

export default withRouter(Create);
