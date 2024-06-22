import { Component } from "react";
import Table from "../../../components/table/table";
import { index } from "./service";
import { ModelResponList, ModelRespon } from "../../../service/response";
import { withRouter, RouterInterface } from "../../../router/router_component";

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
  }

  componentDidMount(): void {
    this.callIndex(undefined);
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
          useBack={false}
          useHeadline
          skeletonRow={5}
          title="Transkip Mahasiswa"
          description="Lihat transkip mahasiswa yang terdaftar"
          column={this.state.index?.response_column ?? []}
          data={this.state.index?.response_data}
          property={this.state.index?.property}
        />
      </div>
    );
  }
}

export default withRouter(Index);
