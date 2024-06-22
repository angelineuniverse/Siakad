import React, { Component } from "react";
import { Model } from "./model";
import Icon from "../icon/icon";
class Collapse extends Component<Model> {
  state: Readonly<{
    open: boolean;
  }>;
  constructor(props: any) {
    super(props);
    this.state = {
      open: false,
    };
  }
  render() {
    return (
      <div className="border border-gray-300">
        <div
          aria-hidden="true"
          onClick={() =>
            this.setState((prevState: Readonly<{ open: boolean }>) => ({
              open: !prevState["open"],
            }))
          }
          className="border cursor-pointer bg-slate-100 border-gray-300 flex gap-x-5 justify-start my-auto px-4 py-2.5 text-sm font-intersemibold"
        >
          <Icon
            icon={this.state.open ? "arrow_down_simple" : "arrow_right_simple"}
            width={20}
            height={20}
          />
          <p>{this.props.title}</p>
        </div>
        {this.state.open && <div className="p-3">{this.props.children}</div>}
      </div>
    );
  }
}

export default Collapse;
