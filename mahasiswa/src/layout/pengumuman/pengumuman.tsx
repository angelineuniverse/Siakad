import { Component } from "react";
import { index } from "./service";
import { previewFile } from "../auth/service";
import { ModelResponList } from "../../service/response";
import { withRouter, RouterInterface } from "../../router/router_component";
import Collapse from "../../components/collapse/collapse";
import Button from "../../components/button/button";
import Skeleton from "../../components/skeleton/skeleton";

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
    await index(params).then((res) => {
      this.setState({
        index: res.data,
      });
    });
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
        <h1 className="mb-10 font-interbold text-xl">Pengumuman</h1>
        <div className="bg-white">
          {!this.state.index && (
            <div>
              <Skeleton type="custom" className="w-full h-10" />
              <Skeleton type="custom" className="w-full h-10" />
              <Skeleton type="custom" className="w-full h-10" />
            </div>
          )}
          {this.state.index?.response_data?.map((event: any) => {
            return (
              <Collapse key={event.id} title={event.title}>
                <p className=" font-interregular text-xs bg-white">
                  {event.description}
                </p>
                {event.file && (
                  <Button
                    title="Lihat File"
                    theme="error"
                    size="extrasmall"
                    className="mt-8"
                    width="block"
                  />
                )}
              </Collapse>
            );
          })}
        </div>
      </div>
    );
  }
}

export default withRouter(Index);
