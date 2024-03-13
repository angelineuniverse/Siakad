import React, { Component, ReactNode } from "react";
import { ModelSelectSearch, sizeInput, sizeLabel } from "./model";
import clsx from "clsx";

class SelectSearch extends Component<ModelSelectSearch> {
  render(): ReactNode {
    return (
      <div className={this.props.className}>
        <p
          className={clsx(
            "mb-1.5 text-gray-700 font-intersemibold tracking-tight inline-block",
            sizeLabel[this.props.size ?? "medium"]
          )}
        >
          {this.props.isRequired && (
            <span className=" text-red-500 font-intersemibold">*</span>
          )}{" "}
          {this.props.label}
        </p>
        <div className=" relative">
          <select
            required={this.props.isRequired}
            defaultValue={0}
            onChange={this.props.onChange}
            className={clsx(
              "border border-gray-400/70 pt-[9px] pb-[9px] pl-2.5 font-interregular text-gray-900 rounded-lg block w-full appearance-none",
              "focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400",
              sizeInput[this.props.size ?? "medium"],
              "bg-no-repeat"
            )}
            style={{
              backgroundImage: `url("data:image/svg+xml,<svg height='10px' width='10px' viewBox='0 0 16 16' fill='%23000000' xmlns='http://www.w3.org/2000/svg'><path d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/></svg>")`,
              backgroundPosition: "calc(100% - 0.75rem) center",
            }}
          >
            <option
              value={0}
              disabled
              className="hidden text-slate-200 font-interregular "
            >
              Pilih item ...
            </option>
            {this.props.chidlren}
          </select>
        </div>
      </div>
    );
  }
}

export default SelectSearch;
