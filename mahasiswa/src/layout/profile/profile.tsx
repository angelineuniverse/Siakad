import { Component, Suspense, lazy } from "react";
import { index, update } from "./service";
import { ModelRespon, formInputs } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import Button from "../../components/button/button";
import Checkbox from "../../components/checkbox/checkbox";
const FormTemplate = lazy(() => import("../../components/form/form"));

class Index extends Component<RouterInterface> {
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
    this.callIndex = this.callIndex.bind(this);
  }

  componentDidMount(): void {
    this.callIndex();
  }

  async callIndex() {
    await index().then((res) => {
      this.setState({
        form: res.data,
      });
    });
  }

  async updated(form: any) {
    this.setState({ loading: true });
    const formArray = form?.basic.concat(form?.detail);
    const forms = formInputs(formArray);
    await update(forms)
      .then((res) => {
        this.setState({ loading: false });
        return this.callIndex();
      })
      .catch((err) => {
        this.setState({ loading: false });
      });
  }

  render() {
    return (
      <div>
        <h1 className="mb-10 font-interbold text-xl">Profile Mahasiswa</h1>
        <Suspense>
          <p className=" font-interbold text-lg">Informasi Umum</p>
          <p className=" font-interregular text-xs mb-4">
            Informasi dasar dari mahasiswa baru
          </p>
          <FormTemplate
            form={this.state.form?.response_data?.basic}
            className="grid grid-cols-3 gap-4"
            lengthLoading={4}
            classNameLoading="grid grid-cols-3 gap-5"
          />
        </Suspense>
        <Suspense>
          <p className=" font-interbold text-lg mt-10">Informasi Detail</p>
          <p className=" font-interregular text-xs mb-4">
            Informasi khusus dari mahasiswa baru
          </p>
          <FormTemplate
            form={this.state.form?.response_data?.detail}
            className="grid grid-cols-3 gap-4"
            lengthLoading={4}
            classNameLoading="grid grid-cols-3 gap-5"
          />
        </Suspense>
        <Suspense>
          <Checkbox
            className="mb-4 mt-10"
            label="Saya bertanggung jawab dengan informasi yang saya masukan"
            onValueChange={(value: boolean) => {
              this.setState({
                checkbox: value,
              });
            }}
          />
          <Button
            isDisable={this.state.checkbox}
            title={"Update Data Mahasiswa"}
            theme="primary"
            size="medium"
            isLoading={this.state.loading}
            width="block"
            onClick={() => this.updated(this.state.form?.response_data)}
          />
        </Suspense>
      </div>
    );
  }
}

export default withRouter(Index);
