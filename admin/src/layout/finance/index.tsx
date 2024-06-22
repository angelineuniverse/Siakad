import { Component, Suspense, lazy } from "react";
import Table from "../../components/table/table";
import { index, periode_show, periode_setujui } from "./service";
import { ModelResponList, ModelRespon } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import moment from "moment";
const Button = lazy(() => import("../../components/button/button"));
const Dialog = lazy(() => import("../../components/dialog/dialog"));

class Index extends Component<RouterInterface> {
  state: Readonly<{
    periodeIndex: ModelRespon | undefined;
    index: ModelResponList | undefined;
    popupSetuju: boolean;
    popupBekukan: boolean;
    loading: boolean;
    detail: any;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      periodeIndex: undefined,
      index: undefined,
      detail: undefined,
      popupSetuju: false,
      popupBekukan: false,
      loading: false,
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

  onEvent(item: any, key: string) {
    switch (key) {
      case "detail":
        this.props.navigate(
          "/finance/" +
            this.props.params?.periodeId +
            "/update/" +
            item.id +
            "/" +
            item?.t_mahasiswa_tabs_id
        );
        break;
      case "setujui":
        this.setState({ popupSetuju: true, detail: item });
        break;
      default:
        this.setState({ popupBekukan: true, detail: item });
        break;
    }
  }

  async updateStatusKRS(item: any, status: number) {
    this.setState({ loading: true });
    await periode_setujui(item.id, {
      m_status_tabs_id: status,
    }).then((res) => {
      this.setState({ loading: false, popupSetuju: false, popupBekukan: false });
      return this.callIndex(undefined);
    });
  }

  render() {
    return (
      <div>
        <Table
          useCreate={false}
          useBack
          useHeadline
          skeletonRow={5}
          title="Management Tagihan Mahasiswa"
          description="Kelola semua tagihan mahasiswa"
          column={this.state.index?.response_column ?? []}
          data={this.state.index?.response_data}
          property={this.state.index?.property}
          onBack={() => this.props.navigate("/akademik/krs")}
          onEvent={(item, key) => this.onEvent(item, key)}
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
            onOpen={this.state.popupSetuju}
            onClose={() => this.setState({ popupSetuju: false })}
            useHeading
            title="Setujui KRS Mahasiswa"
            classHeading="mx-auto uppercase"
            classTitle="text-md font-interbold"
            hideIconClose
            size="w-[350px]"
          >
            <div>
              <div className=" font-interregular text-sm text-center">
                Apabila anda menyetujui data tersebut maka KRS Mahasiswa akan
                diaktifkan dan muncul di Mahasiswa, Apakah anda yakin ingin
                melanjutkan ?
              </div>
              <Button
                title="Setujui KRS"
                theme="primary"
                size="small"
                width="full"
                className="mt-5"
                isLoading={this.state.loading}
                onClick={() =>
                  this.updateStatusKRS(this.state.detail as number, 6)
                }
              ></Button>
            </div>
          </Dialog>
        </Suspense>
        <Suspense>
          <Dialog
            onOpen={this.state.popupBekukan}
            onClose={() => this.setState({ popupBekukan: false })}
            useHeading
            title="Bekukan KRS Mahasiswa"
            classHeading="mx-auto uppercase text-red-500"
            classTitle="text-md font-interbold"
            hideIconClose
            size="w-[350px]"
          >
            <div>
              <div className=" font-interregular text-sm text-center">
                Apabila anda membekukan data tersebut maka KRS Mahasiswa tidak
                akan dapat mengisi nilai KHS, Apakah anda yakin ingin
                melanjutkan ?
              </div>
              <Button
                title="Bekukan KRS"
                theme="error"
                size="small"
                width="full"
                className="mt-5"
                isLoading={this.state.loading}
                onClick={() =>
                  this.updateStatusKRS(this.state.detail as number, 10)
                }
              ></Button>
            </div>
          </Dialog>
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Index);
