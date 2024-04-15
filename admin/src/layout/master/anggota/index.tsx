import { Component } from "react";
import Table from "../../../components/table/table";
import { index } from "./service";
import { ModelResponList } from "../../../service/response";
class Index extends Component {
  state: Readonly<{
    index: ModelResponList | undefined;
  }>;

  constructor(props: any) {
    super(props);
    this.state = {
      index: undefined,
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
          useCreate
          useHeadline
          skeletonRow={5}
          title="Management Anggota"
          description="Atur siapa saja yang dapat akses aplikasi"
          column={this.state.index?.response_column ?? []}
          data={this.state.index?.response_data}
          property={this.state.index?.property}
          delete={() => {}}
        />
      </div>
    );
  }
}

export default Index;
