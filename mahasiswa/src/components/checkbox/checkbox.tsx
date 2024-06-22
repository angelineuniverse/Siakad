import clsx from "clsx";
import React, { Component } from "react";
import { Model } from "./model";

class BreadCrumbs extends Component<Model> {
  render(): React.ReactNode {
    return (
      <div className={clsx("flex justify-start gap-x-3", this.props.className)}>
        <input
          id="checkbox"
          type="checkbox"
          defaultValue={this.props.defaultValue}
          onClick={(event: any) =>
            this.props.onValueChange
              ? this.props.onValueChange(event.target.checked)
              : null
          }
          className={clsx(
            "peer relative w-4 h-4 border rounded-sm focus:outline-none",
            "checked:bg-blue-500 my-auto",
            "after:content-[''] after:w-full after:h-full after:absolute"
          )}
          onChange={this.props.onChange}
        />
        <label htmlFor="checkbox" className="my-auto font-interregular text-xs">
          {this.props.label}
        </label>
      </div>
    );
  }
}

export default BreadCrumbs;
