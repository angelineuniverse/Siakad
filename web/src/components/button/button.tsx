import React, { Component, ReactNode } from "react";
import { ModelButton } from "./model";
import { clsx } from "clsx";

const theme = {
  primary: "bg-blue-600 hover:bg-blue-500 text-white border border-blue-500",
  success:
    "bg-emerald-600 hover:bg-emerald-500 text-white border border-emerald-500",
  error: "bg-rose-600 hover:bg-rose-500 text-white border border-rose-500",
  warning:
    "bg-yellow-600 hover:bg-yellow-500 text-white border border-yellow-500",
  outline: "bg-white hover:bg-white text-black border border-gray-500",
};

const sizeButton = {
  extrasmall: "text-xs px-3 py-1.5",
  small: "text-xsm px-4 pt-2 pb-2.5",
  medium: "text-sm px-4 pt-2 pb-2.5",
  large: "text-lg px-4 pt-2 pb-2.5",
};

const widthButton = {
  block: "w-fit",
  full: "w-full",
};

class Button extends Component<ModelButton> {
  render(): ReactNode {
    return (
      <div className={this.props.className}>
        <button
          onClick={this.props.onClick}
          className={clsx(
            "font-intersemibold rounded-lg",
            "flex justify-center",
            widthButton[this.props.width],
            sizeButton[this.props.size],
            theme[this.props.theme]
          )}
        >
          {this.props.isLoading && (
            <svg
              className="animate-spin mr-2.5 h-4 w-4 text-white"
              xmlns="http://www.w3.org/2000/svg"
              fill="none"
              viewBox="0 0 24 24"
            >
              <circle
                className="opacity-50"
                cx="12"
                cy="12"
                r="10"
                stroke="currentColor"
                strokeWidth="1"
              ></circle>
              <path
                className="opacity-80"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
              ></path>
            </svg>
          )}
          {this.props.title}
        </button>
      </div>
    );
  }
}

export default Button;
