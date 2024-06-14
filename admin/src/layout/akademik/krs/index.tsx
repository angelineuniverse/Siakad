import { Component, Suspense, lazy } from "react";
import Table from "../../../components/table/table";
import { index, deleted, periode_show } from "./service";
import { ModelResponList, ModelRespon } from "../../../service/response";
import { withRouter, RouterInterface } from "../../../router/router_component";
import moment from "moment";
const Button = lazy(() => import("../../../components/button/button"));
const Dialog = lazy(() => import("../../../components/dialog/dialog"));

class Index extends Component<RouterInterface> {
  state: Readonly<{
    periodeIndex: ModelRespon | undefined;
    index: ModelResponList | undefined;
    detail: any;
    form: ModelRespon | undefined;
    create: boolean;
    loading: boolean;
    popupLoading: boolean;
    popupDeleted: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      periodeIndex: undefined,
      index: undefined,
      detail: undefined,
      create: false,
      loading: false,
      popupDeleted: false,
      popupLoading: false,
      form: undefined,
    };
    this.callIndex = this.callIndex.bind(this);
    this.callPeriode = this.callPeriode.bind(this);
  }

  componentDidMount(): void {
    this.callIndex(undefined);
    this.callPeriode();
  }
  async callPeriode() {
    await periode_show(this.props.params?.periodeId).then((res) => {
      this.setState({
        periodeIndex: res.data,
      });
    });
  }
  async callIndex(params: undefined) {
    await index(params).then((res) => {
      this.setState({
        index: res.data,
      });
    });
  }
  async callDelete(params: number) {
    this.setState({ popupLoading: true });
    await deleted(params).then((res) => {
      this.callIndex(undefined);
      this.setState({ popupDeleted: false, popupLoading: false });
    });
  }
  custom(data: any, key: any) {
    return (
      <div>
        {key === "custom_active" && (
          <div className="text-[10px]">
            {data.active === 1 && (
              <p className="px-3 font-intersemibold uppercase py-1 rounded-xl border w-fit bg-emerald-100 border-emerald-700 text-emerald-800">
                active
              </p>
            )}
            {data.active === 0 && (
              <p className="px-3 font-intersemibold uppercase py-1 rounded-xl border w-fit bg-rose-100 border-rose-700 text-rose-800">
                tidak active
              </p>
            )}
          </div>
        )}
      </div>
    );
  }
  customEvent(row: any, key: any) {
    switch (key) {
      case "ubah":
        this.props.navigate(
          "/akademik/krs/" +
            this.props.params?.periodeId +
            "/update/" +
            row?.id +
            "/" +
            row?.t_mahasiswa_tabs_id
        );
        break;
      case "hapus":
        this.setState({ popupDeleted: true, detail: row });
        break;
      default:
        break;
    }
  }
  render() {
    return (
      <div>
        <Table
          useCreate
          useBack
          useHeadline
          skeletonRow={5}
          createTitle="Tambah KRS Mahasiswa"
          title="Management KRS Mahasiswa"
          description="Tambahkan semua krs yang terdaftar"
          column={this.state.index?.response_column ?? []}
          data={this.state.index?.response_data}
          property={this.state.index?.property}
          loadingCreate={this.state.loading}
          onEvent={(row, key) => this.customEvent(row, key)}
          create={() =>
            this.props.navigate(
              "/akademik/krs/" + this.props.params?.periodeId + "/create"
            )
          }
          custom={(data: any, key: any) => this.custom(data, key)}
          onBack={() => this.props.navigate("/akademik/krs")}
          extraHeader={
            <div className="my-4">
              {this.state.periodeIndex && (
                <div className="w-fit text-xs ">
                  <h1 className=" font-interbold text-base">
                    {this.state.periodeIndex?.response_data?.title ?? ""}
                  </h1>
                  <div className="grid grid-cols-2 gap-x-3 w-fit">
                    <span className=" font-intersemibold">Selesai</span>
                    <span>
                      {moment(
                        this.state.periodeIndex?.response_data?.end
                      ).format("d MMMM YYYY")}
                    </span>
                    <span className=" font-intersemibold">Total</span>
                    <span className=" font-intermedium">
                      {this.state.periodeIndex?.response_data?.krs?.length} KRS
                      Mahasiswa
                    </span>
                  </div>
                </div>
              )}
            </div>
          }
        />
        <Suspense>
          <Dialog
            onOpen={this.state.popupDeleted}
            onClose={() => this.setState({ popupDeleted: false })}
            useHeading
            title="Hapus Item"
            classHeading="mx-auto uppercase text-red-600"
            classTitle="text-md font-interbold"
            hideIconClose
            size="w-[350px]"
          >
            <div>
              <div className=" font-interregular text-sm text-center">
                Apabila anda menghapus data tersebut maka seluruh informasi akan
                sepenuhnya dihapus dari system dan tidak dapat di pulihkan,
                Apakah anda yakin ingin melanjutkan ?
              </div>
              <Button
                title="Hapus Data"
                theme="error"
                size="small"
                width="full"
                className="mt-5"
                isLoading={this.state.popupLoading}
                onClick={() => this.callDelete(this.state.detail.id as number)}
              ></Button>
            </div>
          </Dialog>
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Index);
