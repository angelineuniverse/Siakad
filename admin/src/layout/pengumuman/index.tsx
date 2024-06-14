import { Component, Suspense, lazy } from "react";
import Table from "../../components/table/table";
import { index, deleted } from "./service";
import { previewFile } from "../auth/service";
import { ModelResponList } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
const Button = lazy(() => import("../../components/button/button"));
const Dialog = lazy(() => import("../../components/dialog/dialog"));

class Index extends Component<RouterInterface> {
  state: Readonly<{
    index: ModelResponList | undefined;
    detail: any;
    create: boolean;
    loading: boolean;
    popupLoading: boolean;
    popupDeleted: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      index: undefined,
      detail: undefined,
      create: false,
      loading: false,
      popupDeleted: false,
      popupLoading: false,
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
        {key === "file" && (
          <div className="text-[10px]">
            <button
              onClick={() => this.previewFile(data.file)}
              className="bg-blue-600 text-white font-intermedium text-xs px-3 py-1.5 rounded-md"
            >
              Lihat
            </button>
          </div>
        )}
        {key === "active" && (
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

  async previewFile(filename: string) {
    await previewFile("download", "files", filename).then((res) => {
      let link = document.createElement("a");
      link.href = window.URL.createObjectURL(res.data);
      window.open(link.href, "blank");
    });
  }

  render() {
    return (
      <div>
        <Table
          useCreate
          useHeadline
          skeletonRow={5}
          title="Management Pengumuman"
          description="Atur pengumuman untuk semua mahasiswa"
          column={this.state.index?.response_column ?? []}
          data={this.state.index?.response_data}
          property={this.state.index?.property}
          delete={(row) => this.setState({ popupDeleted: true, detail: row })}
          loadingCreate={this.state.loading}
          create={() => this.props.navigate("create")}
          custom={(data: any, key: any) => this.custom(data, key)}
          edit={(row) => this.props.navigate("create/" + row?.id)}
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
