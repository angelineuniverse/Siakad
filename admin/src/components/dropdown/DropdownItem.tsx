import React, { Component, ReactNode } from "react";
import { ModelDropdownItem } from "./model";

class DropdownItem extends Component<ModelDropdownItem> {
  render(): ReactNode {
    return <li className="px-4 cursor-pointer">{this.props.children}</li>;
  }
}

export default DropdownItem;
