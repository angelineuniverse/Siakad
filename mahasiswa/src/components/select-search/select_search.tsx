import React, { Component, ReactNode } from "react";
import { ModelSelectSearch, sizeInput, sizeLabel } from "./model";
import clsx from "clsx";

class SelectSearch extends Component<ModelSelectSearch> {
  state: Readonly<{
    open: boolean;
    placeholder: string;
  }>;
  constructor(props: ModelSelectSearch) {
    super(props);
    this.state = {
      open: false,
      placeholder: "Cari item",
    };
  }

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
        <div
          aria-hidden="true"
          className={clsx(
            "border border-gray-400/70 font-interregular rounded-lg block w-full appearance-none px-0",
            "focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400",
            sizeInput[this.props.size ?? "medium"],
            "bg-no-repeat relative"
          )}
          style={{
            backgroundImage: `url("data:image/svg+xml,<svg height='10px' width='10px' viewBox='0 0 16 16' fill='%23000000' xmlns='http://www.w3.org/2000/svg'><path d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/></svg>")`,
            backgroundPosition: "calc(100% - 0.75rem) center",
          }}
        >
          <input
            type="text"
            readOnly={this.props.readonly}
            className="pt-[9px] pb-[9px] focus:outline-none w-full"
            placeholder={this.props.placeholder ?? "Pilih Item"}
            value={this.props.value_label}
            onInput={(event) => {
              this.props.search_method(event.currentTarget.value);
            }}
          />
        </div>
        <select
          className="hidden"
          value={this.props.value}
          aria-readonly="true"
        ></select>
        {this.props.openChildren && <div>{this.props.children}</div>}
      </div>
    );
  }
}

export default SelectSearch;
