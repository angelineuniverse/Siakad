import { Component, lazy, Suspense } from "react";
import { create, store, edit, update } from "./service";
import { previewFile } from "../../auth/service";
import ArrowLeft from "../../../assets/image/arrow_left.svg";
import { ModelRespon, formInputs } from "../../../service/response";
import { withRouter, RouterInterface } from "../../../router/router_component";
const FormTemplate = lazy(() => import("../../../components/form/form"));
const Button = lazy(() => import("../../../components/button/button"));
const Checkbox = lazy(() => import("../../../components/checkbox/checkbox"));
class Create extends Component<RouterInterface> {
  state: Readonly<{
    form: ModelRespon | undefined;
    loading: boolean;
    checkbox: boolean;
    isCreate: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      loading: false,
      isCreate: true,
      form: undefined,
      checkbox: false,
    };
    this.openCreate = this.openCreate.bind(this);
  }

  componentDidMount(): void {
    this.setState({ isCreate: this.props.params?.id });
    if (this.props.params?.id) {
      this.openEdit();
    } else {
      this.openCreate();
    }
  }

  async openCreate(params?: undefined) {
    this.setState({ form: undefined, checkbox: false });
    await create(params).then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }

  async preview(folder: string, filename: string) {
    await previewFile("download", folder, filename).then((res) => {
      let link = document.createElement("a");
      link.href = window.URL.createObjectURL(res.data);
      window.open(link.href, "blank");
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

  async create(form: any) {
    this.setState({ loading: true });
    const forms = formInputs(form);
    await store(forms)
      .then((res) => {
        this.setState({ loading: false, checkbox: false });
        return this.openCreate();
      })
      .catch((err) => {
        this.setState({ loading: false, checkbox: false });
      });
  }

  async updated(form: any) {
    this.setState({ loading: true });
    const forms = formInputs(form);
    await update(this.props.params?.id, forms)
      .then((res) => {
        this.setState({ loading: false, checkbox: false });
        return this.openEdit();
      })
      .catch((err) => {
        this.setState({ loading: false, checkbox: false });
      });
  }

  render() {
    return (
      <div>
        <div className="flex gap-x-5 mb-12">
          <div
            onClick={() => this.props.navigate("/master/mata-kuliah")}
            aria-hidden="true"
            className="my-auto cursor-pointer"
          >
            <img src={ArrowLeft} alt="ArrowLeft" />
          </div>
          <div>
            <h1 className=" font-interbold text-lg">
              {!this.state.isCreate
                ? "Tambah Mata Kuliah Baru"
                : "Detail Informasi Mata Kuliah"}
            </h1>
            <p className=" font-interregular text-xs">
              {!this.state.isCreate
                ? "Pastikan anda melengkapi informasi dibawah ini"
                : "Detail informasi Mata Kuliah dapat anda lihat dibawah ini"}
            </p>
          </div>
        </div>
        <Suspense>
          <FormTemplate
            form={this.state.form?.response_data}
            className="grid grid-cols-3 gap-4"
            lengthLoading={10}
            preview_file={(key: any, value: any) =>
              this.preview("avatar", value)
            }
            classNameLoading="grid grid-cols-3 gap-5"
          />
        </Suspense>
        <div className="my-8">
          <h5 className=" uppercase font-interbold text-sm text-red-500">
            keterangan
          </h5>
          <ul className=" list-inside list-decimal font-interregular text-xs mt-3">
            <li>Masukan informasi dengan lengkap</li>
            <li>
              Sesuaikan Dosen Pengampu dengan Mata Kuliah yang beliau ajarkan
            </li>
            <li>
              Pastikan anda memilih Semester dan Periode Semester dengan benar
            </li>
            <li>Bobot SKS adalah jumlah SKS pada Mata Kuliah</li>
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
            title={
              !this.state.isCreate ? "Tambah Mata Kuliah" : "Update Mata Kuliah"
            }
            theme="primary"
            size="medium"
            isLoading={this.state.loading}
            width="block"
            onClick={() =>
              !this.state.isCreate
                ? this.create(this.state.form?.response_data)
                : this.updated(this.state.form?.response_data)
            }
          />
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Create);
