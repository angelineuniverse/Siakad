import { Component, lazy, Suspense } from "react";
import {
  edit,
  update,
  store,
  create,
  tagihan,
  tagihan_detail,
  periode_create,
  show,
} from "./service";
import ArrowLeft from "../../assets/image/arrow_left.svg";
import {
  formInputs,
  ModelRespon,
  ModelResponList,
} from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import Table from "../../components/table/table";
import clsx from "clsx";

const FormTemplate = lazy(() => import("../../components/form/form"));
const Button = lazy(() => import("../../components/button/button"));

class Create extends Component<RouterInterface> {
  state: Readonly<{
    form: ModelRespon | undefined;
    formbayaran: ModelRespon | undefined;
    tagihans: ModelRespon | undefined;
    listSelected: ModelResponList | undefined;
    listHistory: ModelResponList | undefined;
    loading: boolean;
    checkbox: boolean;
    loadingTagihan: boolean;
    loadingBayar: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      loading: false,
      loadingTagihan: false,
      loadingBayar: false,
      form: undefined,
      tagihans: undefined,
      formbayaran: undefined,
      listSelected: undefined,
      listHistory: undefined,
      checkbox: false,
    };
    this.currentmatakuliah = this.currentmatakuliah.bind(this);
    this.formTagihan = this.formTagihan.bind(this);
    this.tagihanDetail = this.tagihanDetail.bind(this);
    this.formBayaran = this.formBayaran.bind(this);
    this.tagihanDetail = this.tagihanDetail.bind(this);
    this.updateTagihan = this.updateTagihan.bind(this);
    this.showHistoryBayar = this.showHistoryBayar.bind(this);
  }
  componentDidMount(): void {
    if (this.props.params?.id) {
      this.tagihanDetail();
      this.formTagihan();
      this.formBayaran();
      this.showHistoryBayar();
      this.currentmatakuliah(
        this.props.params?.periodeId,
        this.props.params?.mahasiswaId
      );
    }
  }
  async tagihanDetail() {
    this.setState({ tagihans: undefined });
    await tagihan_detail(this.props.params?.id).then((res) => {
      this.setState({
        tagihans: res.data,
      });
    });
  }
  async updateTagihan(
    krsId: string | undefined,
    data: ModelRespon | undefined
  ) {
    this.setState({ loadingTagihan: true });
    let forms = formInputs(data?.response_data);
    forms.append("id", krsId ?? "");
    await tagihan(forms).then((res) => {
      this.setState({ loadingTagihan: false });
      this.tagihanDetail();
      return this.formTagihan();
    });
  }
  async currentmatakuliah(
    periodeId: string | undefined,
    mahasiswaId: string | number | undefined
  ) {
    await periode_create(periodeId, mahasiswaId).then((res) => {
      this.setState({
        listSelected: res.data,
      });
    });
  }
  async formTagihan() {
    this.setState({ form: undefined });
    await edit(this.props.params?.id).then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }
  async formBayaran() {
    this.setState({ formbayaran: undefined, checkbox: false });
    await create().then((res) => {
      this.setState({
        formbayaran: res.data,
      });
    });
  }
  async stored() {
    this.setState({ loadingBayar: true });
    const form = formInputs(this.state.formbayaran?.response_data);
    form.append("t_krs_tabs_id", this.props.params?.id ?? "");
    await store(form)
      .then((res) => {
        this.setState({ loadingBayar: false });
        this.formTagihan();
        this.formBayaran();
        this.tagihanDetail();
        return this.showHistoryBayar();
      })
      .catch((err) => {
        this.setState({ loadingBayar: false });
      });
  }
  async showHistoryBayar() {
    await show(this.props.params?.id).then((res) => {
      this.setState({
        listHistory: res.data,
      });
    });
  }
  async updateStatus(item: any, key: string) {
    let m_status_tabs_id: number = 8;
    if (key === "valid") {
      m_status_tabs_id = 9;
    }
    await update(item.id, {
      m_status_tabs_id: m_status_tabs_id,
    }).then((res) => {
      this.tagihanDetail();
      return this.showHistoryBayar();
    });
  }

  render() {
    return (
      <div>
        <div className="flex gap-x-5 mb-10">
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
        {this.state.tagihans && (
          <div
            className={clsx(
              "border px-4 py-3 rounded-md mb-8",
              this.state.tagihans?.response_data?.status === 1
                ? "border-green-600 bg-green-50 text-green-800"
                : this.state.tagihans?.response_data?.status === 2
                ? "border-orange-600 bg-orange-50 text-orange-800"
                : "border-red-600 bg-red-50 text-red-800"
            )}
          >
            <p className=" font-intersemibold text-sm">
              Tagihan{" "}
              {this.state.tagihans?.response_data?.status === 1
                ? "Sudah Lunas"
                : this.state.tagihans?.response_data?.status === 2
                ? "Kelebihan"
                : "Belum Lunas"}
            </p>
            <p className=" font-interregular text-xs">
              Sisa Tagihan Mahasiswa{" "}
              {this.state.tagihans?.response_data?.status === 2 && "Kurangi"}{" "}
              <b>
                {this.state.tagihans?.response_data?.tagihan.toLocaleString(
                  "id-ID",
                  {
                    style: "currency",
                    currency: "IDR",
                    minimumFractionDigits: 0,
                  }
                )}
              </b>
            </p>
          </div>
        )}
        <Suspense>
          <Table
            useCreate={false}
            useHeadline
            skeletonRow={5}
            title="Mata Kuliah Yang Dipilih"
            description="Mata Kuliah yang akan ditempuh mahasiswa"
            column={this.state.listSelected?.response_column ?? []}
            data={this.state.listSelected?.response_data}
            property={this.state.listSelected?.property}
          />
        </Suspense>
        <div className="my-8 h-[0.8px] bg-gray-500 w-full"></div>
        <Suspense>
          <div className="grid grid-cols-2 gap-5">
            <div className="container">
              <p className=" font-interbold text-lg">Informasi Tagihan</p>
              <p className=" font-interregular text-xs mb-4">
                Informasi dasar dari tagihan mahasiswa
              </p>
              <FormTemplate
                form={this.state.form?.response_data}
                className="grid grid-cols-1 gap-4"
                lengthLoading={1}
                classNameLoading="grid grid-cols-1 gap-5"
              />
              <Button
                title="Simpan Biaya Tagihan"
                theme="primary"
                size="medium"
                className="mt-6"
                isLoading={this.state.loadingTagihan}
                width="block"
                onClick={() => {
                  this.updateTagihan(this.props.params?.id, this.state.form);
                }}
              />
            </div>
            <div className="container">
              <p className=" font-interbold text-lg">Bayar Tagihan</p>
              <p className=" font-interregular text-xs mb-4">
                Masukan uang masuk dari mahasiswa
              </p>
              <FormTemplate
                form={this.state.formbayaran?.response_data}
                className="grid grid-cols-1 gap-4"
                lengthLoading={1}
                classNameLoading="grid grid-cols-1 gap-5"
              />
              <Button
                title="Bayar Tagihan"
                theme="success"
                size="medium"
                className="mt-6"
                isLoading={this.state.loadingBayar}
                width="block"
                onClick={() => {
                  this.stored();
                }}
              />
            </div>
          </div>
        </Suspense>
        <Suspense>
          <Table
            useCreate={false}
            useHeadline
            skeletonRow={3}
            className="mt-10"
            title="History Pembayaran Tagihan"
            description="Informasi dari history pembayaran Mahasiswa"
            column={this.state.listHistory?.response_column ?? []}
            data={this.state.listHistory?.response_data}
            property={this.state.listHistory?.property}
            onEvent={(item, key) => this.updateStatus(item, key)}
          />
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Create);
