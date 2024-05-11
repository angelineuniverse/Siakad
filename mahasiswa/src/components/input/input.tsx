import React, { Component, ReactNode } from "react";
import { ModelInput } from "./model";
import { clsx } from "clsx";
import Icon from "../icon/icon";

const sizeLabel = {
  small: "text-xsm",
  medium: "text-sm",
  large: "text-lg",
};

const sizeInput = {
  small: "text-xsm px-2.5 placeholder:text-xsm",
  medium: "text-sm px-2.5 placeholder:text-sm",
  large: "text-lg px-3 placeholder:text-lg",
};

class Input extends Component<ModelInput> {
  state: Readonly<{
    visiblePassword: boolean;
  }>;
  constructor(props: ModelInput) {
    super(props);

    this.state = {
      visiblePassword: false,
    };
  }

  changeVisiblePassword(visible: boolean) {
    return visible ? "text" : "password";
  }
  render(): ReactNode {
    return (
      <div className={this.props.className}>
        <p
          className={clsx(
            "mb-1.5 font-intersemibold tracking-tight inline-block",
            sizeLabel[this.props.size ?? "medium"]
          )}
        >
          {this.props.isRequired && (
            <span className=" text-red-500 font-intersemibold">*</span>
          )}{" "}
          {this.props.label}
        </p>
        <div className="relative">
          <input
            required={this.props.isRequired}
            readOnly={this.props.readonly}
            type={
              this.props.type === "password"
                ? this.changeVisiblePassword(this.state.visiblePassword)
                : this.props.type
            }
            className={clsx(
              "placeholder:font-interregular placeholder:text-slate-400 font-interregular",
              "border border-gray-400/70 p-2 text-gray-900 rounded-lg focus:outline-none focus:ring-1 focus:ring-blue-400 focus:border-blue-400 block w-full",
              sizeInput[this.props.size ?? "medium"],
              this.props.type === "password" ? "pr-10" : ""
            )}
            defaultValue={this.props.defaultValue}
            placeholder={this.props.placeholder ?? "Tulis disini"}
            onChange={this.props.onChange}
            onInput={(event) =>
              this.props.onValueChange
                ? this.props.onValueChange(event.currentTarget.value)
                : null
            }
          />
          {this.props.type === "password" && (
            <div className="absolute inset-y-0 right-0 flex items-center pe-3 top-0 bottom-0 cursor-pointer">
              {this.state.visiblePassword && (
                <Icon
                  icon="eye"
                  width={15}
                  height={15}
                  onClick={() => this.setState({ visiblePassword: false })}
                />
              )}
              {!this.state.visiblePassword && (
                <Icon
                  icon="hideEye"
                  width={15}
                  height={15}
                  onClick={() => this.setState({ visiblePassword: true })}
                />
              )}
            </div>
          )}
        </div>
        <i className="text-gray-500 font-interregular text-xs">
          {this.props.description}
        </i>
      </div>
    );
  }
}

export default Input;
