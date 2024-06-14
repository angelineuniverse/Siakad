import { Component } from "react";
import Table from "../../components/table/table";
import { index, periode_show } from "./service";
import { ModelResponList, ModelRespon } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import moment from "moment";

class Index extends Component<RouterInterface> {
  state: Readonly<{
    periodeIndex: ModelRespon | undefined;
    index: ModelResponList | undefined;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      periodeIndex: undefined,
      index: undefined,
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
          show={(row) =>
            this.props.navigate(
              "/finance/" +
                this.props.params?.periodeId +
                "/update/" +
                row.id +
                "/" +
                row?.t_mahasiswa_tabs_id
            )
          }
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
      </div>
    );
  }
}

export default withRouter(Index);
