import { Component, lazy, Suspense } from "react";
import { createForm, store } from "./service";
import ArrowLeft from "../../../assets/image/arrow_left.svg";
import { ModelRespon, formInputs } from "../../../service/response";
import { withRouter, RouterInterface } from "../../../router/router_component";
const FormTemplate = lazy(() => import("../../../components/form/form"));
const Button = lazy(() => import("../../../components/button/button"));
const Checkbox = lazy(() => import("../../../components/checkbox/checkbox"));
class Create extends Component<RouterInterface> {
  state: Readonly<{
    form: ModelRespon | undefined;
    loading: boolean;
    checkbox: boolean;
  }>;

  constructor(props: RouterInterface) {
    super(props);
    this.state = {
      loading: false,
      form: undefined,
      checkbox: false,
    };
    this.openCreate = this.openCreate.bind(this);
  }

  componentDidMount(): void {
    this.openCreate();
  }

  async openCreate(params?: undefined) {
    this.setState({ form: undefined, checkbox: false });
    await createForm(params).then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }

  async create(form: any) {
    this.setState({ loading: true });
    const forms = formInputs(form);
    await store(forms)
      .then((res) => {
        this.setState({ loading: false });
        return this.openCreate();
      })
      .catch((err) => {
        this.setState({ loading: false });
      });
  }

  render() {
    return (
      <div>
        <div className="flex gap-x-5 mb-12">
          <div
            onClick={() => this.props.navigate(-1)}
            aria-hidden="true"
            className="my-auto cursor-pointer"
          >
            <img src={ArrowLeft} alt="ArrowLeft" />
          </div>
          <div>
            <h1 className=" font-interbold text-lg">Tambah Member Baru</h1>
            <p className=" font-interregular text-xs">
              Pastikan anda melengkapi informasi dibawah ini
            </p>
          </div>
        </div>
        <Suspense>
          <FormTemplate
            form={this.state.form?.response_data}
            className="grid grid-cols-3 gap-4"
            lengthLoading={10}
            classNameLoading="grid grid-cols-3 gap-5"
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
        <Suspense>
          <Checkbox
            className="mb-4"
            label="Saya bertanggung jawab dengan informasi yang saya masukan"
            onValueChange={(value: boolean) => {
              this.setState({
                checkbox: value,
              });
            }}
          />
          <Button
            isDisable={this.state.checkbox}
            title="Tambah Member"
            theme="primary"
            size="medium"
            isLoading={this.state.loading}
            width="block"
            onClick={() => this.create(this.state.form?.response_data)}
          />
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Create);
