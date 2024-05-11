import { Component, Suspense, lazy } from "react";
import Table from "../../../components/table/table";
import { index, createForm } from "./service";
import { ModelResponList, ModelRespon } from "../../../service/response";
const Dialog = lazy(() => import("../../../components/dialog/dialog"));
const FormTemplate = lazy(() => import("../../../components/form/form"));
class Index extends Component {
  state: Readonly<{
    index: ModelResponList | undefined;
    form: ModelRespon | undefined;
    create: boolean;
    loading: boolean;
  }>;

  constructor(props: any) {
    super(props);
    this.state = {
      index: undefined,
      create: false,
      loading: false,
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

  async openCreate(params?: undefined) {
    await createForm(params).then((res) => {
      this.setState({
        loading: false,
        create: true,
        form: res.data,
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
          loadingCreate={this.state.loading}
          create={() => {
            this.setState({ loading: true });
            this.openCreate();
          }}
        />
        <Suspense>
          <Dialog
            onOpen={this.state.create}
            onClose={() => this.setState({ create: false })}
            useHeading
            size="small"
            title="Buat Anggota Baru"
            subtitle="Lengkapi semua informasi dibawah ini"
          >
            <div>
              <Suspense>
                <FormTemplate form={this.state.form?.response_data} />
              </Suspense>
            </div>
          </Dialog>
        </Suspense>
      </div>
    );
  }
}

export default Index;
