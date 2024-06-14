import React, { Component } from "react";
import { Model } from "./model";

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
          aria-readonly="false"
          onClick={() => this.setState({ open: true })}
          className="border border-gray-300 flex justify-start my-auto px-3 py-2 text-sm font-intersemibold"
        >
          <p>{this.props.title}</p>
        </div>
        <div className="p-3">{this.props.children}</div>
      </div>
    );
  }
}

export default Collapse;
