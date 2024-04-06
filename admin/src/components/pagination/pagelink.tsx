import clsx from "clsx";
import React, { Component, ReactNode } from "react";
import { ModelPaginationLink } from "./model";

class PageLink extends Component<ModelPaginationLink> {
  constructor(props: any) {
    super(props);
  }
  render(): ReactNode {
    return (
      <button
        className={clsx(
          "border rounded py-2 px-3 hover:border-blue-500",
          this.props.className
        )}
        disabled={this.props.isActive}
      >
        {this.props.children}
      </button>
    );
  }
}

export default PageLink;
