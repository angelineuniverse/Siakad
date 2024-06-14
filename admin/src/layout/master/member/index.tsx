import { Component, Suspense, lazy } from "react";
import Table from "../../../components/table/table";
import { index, deleted, edit, update } from "./service";
import {
  ModelResponList,
  ModelRespon,
  formInputs,
} from "../../../service/response";
import { withRouter, RouterInterface } from "../../../router/router_component";
const Button = lazy(() => import("../../../components/button/button"));
const Dialog = lazy(() => import("../../../components/dialog/dialog"));
const FormTemplate = lazy(() => import("../../../components/form/form"));

class Index extends Component<RouterInterface> {
  state: Readonly<{
    index: ModelResponList | undefined;
    detail: any;
    form: ModelRespon | undefined;
    create: boolean;
    loading: boolean;
    popupLoading: boolean;
    popupDeleted: boolean;
    popupEdit: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      index: undefined,
      detail: undefined,
      create: false,
      loading: false,
      popupDeleted: false,
      popupEdit: false,
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
        {key === "custom_status" && (
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

  async callEdit(params: any) {
    this.setState({ popupEdit: true, detail: params });
    await edit(params?.id).then((res) => {
      this.setState({ form: res.data });
    });
  }

  async callUpdate(id: number, params: any) {
    this.setState({ popupLoading: true });
    const forms = formInputs(params);
    await update(id, forms).then((res) => {
      this.setState({
        popupLoading: false,
        popupEdit: false,
      });
      this.callIndex(undefined);
    });
  }

  render() {
    return (
      <div>
        <Table
          useCreate
          useHeadline
          skeletonRow={5}
          title="Management Member"
          description="Atur siapa saja yang dapat akses aplikasi"
          column={this.state.index?.response_column ?? []}
          data={this.state.index?.response_data}
          property={this.state.index?.property}
          delete={(row) => this.setState({ popupDeleted: true, detail: row })}
          loadingCreate={this.state.loading}
          create={() => this.props.navigate("create")}
          custom={(data: any, key: any) => this.custom(data, key)}
          edit={(row) => this.callEdit(row)}
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
          <Dialog
            onOpen={this.state.popupEdit}
            onClose={() => this.setState({ popupEdit: false, form: undefined })}
            useHeading
            title="Detail Informasi Member"
            subtitle="Klik simpan untuk mengubah informasi"
            classTitle="text-md font-interbold uppercase"
            size="small"
          >
            <div>
              <Suspense>
                <FormTemplate
                  form={this.state.form?.response_data}
                  className="grid grid-cols-1 gap-4"
                  lengthLoading={6}
                  classNameLoading="grid grid-cols-1 gap-5"
                />
              </Suspense>
              <div className="my-8">
                <h5 className=" uppercase font-interbold text-sm text-red-500">
                  keterangan
                </h5>
                <ul className=" list-inside list-decimal font-interregular text-xs mt-3">
                  <li>Masukan informasi dengan lengkap</li>
                  <li>Gunakan awalan 62 untuk informasi nomor Whatsapp</li>
                </ul>
              </div>
              <Button
                title="Update Data"
                theme="primary"
                size="small"
                width="full"
                className="mt-5"
                isLoading={this.state.popupLoading}
                onClick={() =>
                  this.callUpdate(
                    this.state.detail?.id,
                    this.state.form?.response_data
                  )
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
