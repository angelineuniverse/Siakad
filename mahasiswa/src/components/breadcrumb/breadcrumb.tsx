import React, { Component, ReactNode } from "react";
import { Link } from "react-router-dom";

class BreadCrumbs extends Component<any> {
  breadcrumb = () => {
    let currentLink: any = "";
    const crumb = this.props.location.pathname
      .split("/")
      .filter((x: any) => x !== "")
      .map((mp: any) => {
        currentLink += `/${mp}`;

        return (
          <div
            key={mp}
            className=" after:content-['-'] last:after:hidden after:ml-0.5"
          >
            <Link
              to={currentLink}
              className=" font-intersemibold text-xs text-indigo-800"
            >
              {mp}
            </Link>
          </div>
        );
      });
    return crumb;
  };
  render(): ReactNode {
    return (
      <div className="flex justify-start mb-3 gap-x-1">{this.breadcrumb()}</div>
    );
  }
}

export default BreadCrumbs;
