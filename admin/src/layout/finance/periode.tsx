import { Component } from "react";
import Table from "../../components/table/table";
import { Colorsclass } from "../../utils/colors";
import { periode_index } from "./service";
import { ModelResponList } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import clsx from "clsx";

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
    this.callIndex(undefined);
  }

  async callIndex(params: undefined) {
    await periode_index(params).then((res) => {
      this.setState({
        index: res.data,
      });
    });
  }

  custom(data: any, key: any) {
    return (
      <div>
        {key === "custom_status" && (
          <div className="text-[10px]">
            <p
              className={clsx(
                "px-3 font-intersemibold uppercase py-1 rounded-xl w-fit",
                Colorsclass.border[data?.status?.color],
                Colorsclass.background[data?.status?.color],
                `text-${data?.status?.color}-800`
              )}
            >
              {data?.status?.title}
            </p>
          </div>
        )}
      </div>
    );
  }

  render() {
    return (
      <div>
        <Table
          useCreate={false}
          useHeadline
          skeletonRow={5}
          title="Management Periode KRS"
          description="Atur periode yang aktif untuk KRS"
          column={this.state.index?.response_column ?? []}
          data={this.state.index?.response_data}
          property={this.state.index?.property}
          custom={(data: any, key: any) => this.custom(data, key)}
          show={(row) => this.props.navigate("detail/" + row.id)}
        />
      </div>
    );
  }
}

export default withRouter(Index);
