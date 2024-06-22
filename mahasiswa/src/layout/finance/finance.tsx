import { Component, Suspense } from "react";
import { index } from "./service";
import { ModelResponList } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import Table from "../../components/table/table";

class Index extends Component<RouterInterface> {
  state: Readonly<{
    index: ModelResponList | undefined;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      index: undefined,
    };
    this.callIndex = this.callIndex.bind(this);
  }

  componentDidMount(): void {
    this.callIndex();
  }

  async callIndex() {
    await index().then((res) => {
      this.setState({
        index: res.data,
      });
    });
  }

  render() {
    return (
      <div>
        <div className="mb-10">
          <h1 className="font-interbold text-xl">Riwayat Bayar Anda</h1>
          <p className=" font-interregular text-xs">
            Lihat semua riwayat pembayaran kuliah anda
          </p>
        </div>
        <Suspense>
          <Table
            useCreate={false}
            useBack={false}
            useHeadline={false}
            skeletonRow={5}
            column={this.state.index?.response_column ?? []}
            data={this.state.index?.response_data}
            property={this.state.index?.property}
          />
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Index);
