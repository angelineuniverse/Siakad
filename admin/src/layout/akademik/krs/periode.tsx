import { Component, Suspense, lazy } from "react";
import Table from "../../../components/table/table";
import { Colorsclass } from "../../../utils/colors";
import {
  periode_index,
  periode_deleted,
  periode_edit,
  periode_update,
  periode_create,
  periode_store,
} from "./service";
import {
  ModelResponList,
  ModelRespon,
  formInputs,
} from "../../../service/response";
import { withRouter, RouterInterface } from "../../../router/router_component";
import clsx from "clsx";
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
    popupCreate: boolean;
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
      popupCreate: false,
      popupLoading: false,
      form: undefined,
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

  async callDelete(params: number) {
    this.setState({ popupLoading: true });
    await periode_deleted(params).then((res) => {
      this.callIndex(undefined);
      this.setState({ popupDeleted: false, popupLoading: false });
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

  async callEdit(params: any) {
    this.setState({ popupEdit: true, detail: params, form: undefined });
    await periode_edit(params?.id).then((res) => {
      this.setState({ form: res.data });
    });
  }

  async callForm() {
    this.setState({ popupCreate: true });
    await periode_create().then((res) => {
      this.setState({ form: res.data });
    });
  }

  async callCreate(form: any) {
    this.setState({ popupLoading: true });
    const forms = formInputs(form);
    await periode_store(forms).then((res) => {
      this.setState({ popupCreate: false, popupLoading: false });
      this.callIndex(undefined);
    });
  }

  async callUpdate(id: string, params: any) {
    this.setState({ popupLoading: true });
    const forms = formInputs(params);
    await periode_update(id, forms).then((res) => {
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
          createTitle="Buat Periode Baru"
          title="Management Periode KRS Baru"
          description="Atur periode yang aktif untuk KRS"
          column={this.state.index?.response_column ?? []}
          data={this.state.index?.response_data}
          property={this.state.index?.property}
          delete={(row) => this.setState({ popupDeleted: true, detail: row })}
          loadingCreate={this.state.loading}
          create={() => this.callForm()}
          custom={(data: any, key: any) => this.custom(data, key)}
          edit={(row) => this.callEdit(row)}
          show={(row) => this.props.navigate("detail/" + row.id)}
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
            onOpen={this.state.popupCreate}
            onClose={() =>
              this.setState({ popupCreate: false, form: undefined })
            }
            useHeading
            title="Buat Periode Baru"
            subtitle="Klik simpan untuk membuat periode baru"
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
                  <li>
                    Periode yang telah melewati dari waktu selesainya akan
                    automatis closed
                  </li>
                </ul>
              </div>
              <Button
                title="Buat Periode"
                theme="primary"
                size="small"
                width="full"
                className="mt-5"
                isLoading={this.state.popupLoading}
                onClick={() => this.callCreate(this.state.form?.response_data)}
              ></Button>
            </div>
          </Dialog>
          <Dialog
            onOpen={this.state.popupEdit}
            onClose={() => this.setState({ popupEdit: false, form: undefined })}
            useHeading
            title="Detail Informasi Periode"
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
