import { Component, Suspense, lazy } from "react";
import { index, selectForm } from "./service";
import { ModelRespon, ModelResponList } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import Table from "../../components/table/table";
import Skeleton from "../../components/skeleton/skeleton";
const Select = lazy(() => import("../../components/select/select"));

class Index extends Component<RouterInterface> {
  state: Readonly<{
    index: ModelResponList | undefined;
    form: ModelRespon | undefined;
    active: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      index: undefined,
      form: undefined,
      active: false,
    };
    this.callIndex = this.callIndex.bind(this);
    this.callSelectForm = this.callSelectForm.bind(this);
  }

  componentDidMount(): void {
    this.callSelectForm(undefined);
  }

  async callIndex(params: number) {
    this.setState({
      active: true,
      index: undefined,
    });
    await index(params).then((res) => {
      this.setState({
        index: res.data,
      });
    });
  }
  async callSelectForm(params: undefined) {
    await selectForm(params).then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }

  render() {
    return (
      <div>
        <div className="mb-10">
          <h1 className="font-interbold text-xl">Hasil Studi Anda</h1>
          <p className=" font-interregular text-xs">
            Cari semester anda untuk melihat setiap hasil studi
          </p>
        </div>
        <Suspense>
          <div className="mb-7 w-2/12">
            {!this.state.form && (
              <Skeleton type="custom" className="w-full h-9" />
            )}
            {this.state.form && (
              <Select
                isRequired={true}
                key={"semester"}
                placeholder={"Pilih Semester"}
                label={"Pilih Semester"}
                onClick={(event: any) => {
                  this.callIndex(event.target.value);
                }}
                keyValue={"id"}
                keyoption={"title"}
                options={this.state.form?.response_data}
              />
            )}
          </div>
          {!this.state.active && !this.state.index && (
            <p className=" font-interregular text-center text-xs">
              Harap pilih semester anda terlebih dahulu
            </p>
          )}
          {this.state.active && this.state.index && (
            <Table
              useCreate={false}
              useBack={false}
              useHeadline={false}
              skeletonRow={5}
              column={this.state.index?.response_column ?? []}
              data={this.state.index?.response_data}
              property={this.state.index?.property}
            />
          )}
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Index);
