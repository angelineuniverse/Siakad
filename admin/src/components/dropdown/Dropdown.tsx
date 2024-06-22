import React, { Component, ReactNode } from "react";
import { ModelDropdown } from "./model";
import clsx from "clsx";

const DirectionStyle = {
  "bottom-right": "right-0",
  "bottom-left": "left-0",
  left: "left-0",
  right: "right-0",
};

class Dropdown extends Component<ModelDropdown> {
  state: Readonly<{
    show: boolean;
  }>;
  constructor(props: ModelDropdown) {
    super(props);
    this.state = {
      show: false,
    };
  }

  render(): ReactNode {
    return (
      <div>
        <div className={clsx(" w-fit relative")}>
          <div
            aria-hidden="true"
            onClick={() =>
              this.setState((prevState: Readonly<{ show: boolean }>) => ({
                show: !prevState["show"],
              }))
            }
          >
            {this.props.trigger}
          </div>
          {this.state.show && (
            <ul
              className={clsx(
                DirectionStyle[this.props.direction ?? "bottom-right"],
                "min-w-max absolute mt-2 divide-y text-gray-900 border border-gray-300 rounded-md shadow-lg divide-gray-300",
                "overflow-hidden bg-white"
              )}
            >
              {this.props.children}
            </ul>
          )}
        </div>
      </div>
    );
  }
}

export default Dropdown;
