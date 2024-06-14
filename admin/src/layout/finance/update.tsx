import { Component, lazy, Suspense } from "react";
import { edit, update, selectedMatakuliah } from "./service";
import ArrowLeft from "../../assets/image/arrow_left.svg";
import { ModelRespon } from "../../service/response";
import { show } from "../master/mahasiswa/service";
import { withRouter, RouterInterface } from "../../router/router_component";

const FormTemplate = lazy(() => import("../../components/form/form"));
const Button = lazy(() => import("../../components/button/button"));
const Checkbox = lazy(() => import("../../components/checkbox/checkbox"));

class Create extends Component<RouterInterface> {
  state: Readonly<{
    form: ModelRespon | undefined;
    mahasiswa: ModelRespon | undefined;
    matakuliah: undefined | ModelRespon;
    listSelected: Array<any>;
    loading: boolean;
    checkbox: boolean;
    loadingTagihan: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      loading: false,
      loadingTagihan: false,
      form: undefined,
      listSelected: [],
      mahasiswa: undefined,
      checkbox: false,
      matakuliah: undefined,
    };
    this.currentmatakuliah = this.currentmatakuliah.bind(this);
    this.openEdit = this.openEdit.bind(this);
  }

  componentDidMount(): void {
    if (this.props.params?.id) {
      this.openEdit();
      this.currentmatakuliah(
        this.props.params?.periodeId,
        this.props.params?.mahasiswaId
      );
    }
  }
  async updateTagihan(
    krsId: string | undefined,
    data: ModelRespon | undefined
  ) {
    await update(krsId, {
      tagihan: data,
    }).then((res) => {
      this.openEdit();
    });
  }
  async currentmatakuliah(
    periodeId: string | undefined,
    mahasiswaId: string | number | undefined
  ) {
    await selectedMatakuliah(periodeId, mahasiswaId).then((res) => {
      this.setState({
        listSelected: res.data.response_data?.matakuliah,
      });
    });
  }
  async openEdit() {
    this.setState({ form: undefined, checkbox: false });
    await edit(this.props.params?.id).then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }
  async updated(matakuliah: any) {
    this.setState({ loading: true });
    await update(this.props.params?.id, {
      t_mahasiswa_tabs_id: this.props.params?.mahasiswaId,
      t_periode_tabs_id: this.props.params?.periodeId,
      t_krs_tabs_id: this.props.params?.id,
      matakuliah: matakuliah,
    })
      .then((res) => {
        this.setState({ loading: false });
        this.currentmatakuliah(
          this.props.params?.periodeId,
          this.props.params?.mahasiswaId
        );
        return this.openEdit();
      })
      .catch((err) => {
        this.setState({ loading: false });
      });
  }
  remoted(key: string, event: any) {
    show(event).then((res) => {
      const mahasiswa = res.data.response_data;
      console.log(mahasiswa);

      this.setState({
        mahasiswa: res.data,
      });
      for (let item of this.state.form!.response_data) {
        if (item.key === "nim") {
          item[item.key] = mahasiswa.nim;
          this.setState({ [item[item.key]]: mahasiswa.nim });
        }
        if (item.key === "semester") {
          item[item.key] = mahasiswa.semester_active?.semester?.title;
          this.setState({
            [item[item.key]]: mahasiswa.semester_active?.semester?.title,
          });
        }
        if (item.key === "periode_semester") {
          item[item.key] = mahasiswa.semester_active?.semester_periode?.title;
          this.setState({
            [item[item.key]]:
              mahasiswa.semester_active?.semester_periode?.title,
          });
        }
      }
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
            <h1 className=" font-interbold text-lg">
              Detail Informasi Tagihan Mahasiswa
            </h1>
            <p className=" font-interregular text-xs">
              Detail informasi Tagihan Mahasiswa dapat anda lihat dibawah ini
            </p>
          </div>
        </div>
        <div className="border-b border-gray-400 mb-10">
          <div className="flex justify-start w-full mb-8 ">
            <div className="mr-auto my-auto">
              <p className="font-interbold text-lg">Mata Kuliah Yang Dipilih</p>
              <p className=" font-interregular text-xs mb-4">
                Mata Kuliah yang akan ditempuh mahasiswa
              </p>
            </div>
          </div>
          <div className="overflow-hidden border mb-10 border-gray-300">
            <table className="w-full h-auto min-w-full divide-y divide-gray-200">
              <thead className="bg-gradient-to-b from-gray-100 to-gray-100">
                <tr className="text-xs font-intermedium">
                  <th className="py-3 text-center text-xs px-4">Code</th>
                  <th className="py-3 text-start text-xs px-4">Mata Kuliah</th>
                  <th className="py-3 text-start text-xs px-4">Dosen</th>
                  <th className="py-3 text-center text-xs px-4">Bobot SKS</th>
                  <th className="py-3 text-center text-xs px-4">Jadwal</th>
                </tr>
              </thead>
              <tbody>
                {this.state.listSelected?.map((item: any, index: number) => {
                  return (
                    <tr
                      key={item?.detail_matakuliah?.id ?? item.id}
                      className="text-xs text-start font-interregular border-b border-gray-400"
                    >
                      <td className="text-center">
                        {item?.detail_matakuliah?.code ?? item.code}
                      </td>
                      <td className="px-4 text-justify text-xsm">
                        {item?.detail_matakuliah?.title ?? item.title}
                      </td>
                      <td className="px-4 text-justify">
                        {item?.detail_matakuliah?.dosen?.name ??
                          item.dosen?.name}
                      </td>
                      <td className="px-4 text-center">
                        {item?.detail_matakuliah?.bobot_sks ?? item.bobot_sks}
                      </td>
                      <td className="py-2 text-center font-intermedium">
                        <p className=" font-interbold">
                          {item?.detail_matakuliah?.days ?? item.days}
                        </p>
                        <p className=" text-blue-800">
                          {item?.detail_matakuliah?.times ?? item.times}
                        </p>
                      </td>
                    </tr>
                  );
                })}
              </tbody>
            </table>
            {this.state.listSelected.length < 1 && (
              <p className="py-3 text-center font-interregular text-xs">
                Belum ada matakuliah yang dipilih{" "}
              </p>
            )}
          </div>
        </div>
        <Suspense>
          <p className=" font-interbold text-lg">Informasi Tagihan</p>
          <p className=" font-interregular text-xs mb-4">
            Informasi dasar dari tagihan mahasiswa
          </p>
          <FormTemplate
            form={this.state.form?.response_data}
            className="grid grid-cols-3 gap-4"
            lengthLoading={4}
            remote_change={(key: string, event: any) =>
              this.remoted(key, event)
            }
            classNameLoading="grid grid-cols-3 gap-5"
          />
          <Button
            title="Simpan Biaya Tagihan"
            theme="primary"
            size="medium"
            className="mt-6"
            isLoading={this.state.loadingTagihan}
            width="block"
            onClick={() => {
              this.updateTagihan(
                this.props.params?.id,
                this.state.form?.response_data
              );
            }}
          />
        </Suspense>
        <Suspense>
          <p className="mt-10 font-interbold text-lg">
            History Pembayaran Tagihan
          </p>
          <p className=" font-interregular text-xs mb-4">
            Informasi dari history pembayaran Mahasiswa
          </p>
        </Suspense>
        <div className="my-8">
          <h5 className=" uppercase font-interbold text-sm text-red-500">
            keterangan
          </h5>
          <ul className=" list-inside list-decimal font-interregular text-xs mt-3">
            <li>Masukan informasi dengan lengkap</li>
          </ul>
        </div>
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
            title="Update KRS Mahasiswa"
            theme="primary"
            size="medium"
            isLoading={this.state.loading}
            width="block"
            onClick={() => {
              this.updated(this.state.listSelected);
            }}
          />
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Create);
