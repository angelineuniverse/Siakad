import React, { Component, ReactNode } from "react";
import { ModelNotification } from "./model";
import clsx from "clsx";
import Icon from "../icon/icon";

class Notification extends Component<ModelNotification> {
  state: Readonly<{
    tema: any;
  }>;

  constructor(props: ModelNotification) {
    super(props);
    this.state = {
      tema: {
        success: "bg-green-100 text-green-800 border border-green-500",
        error: "bg-red-100 text-red-800 border border-red-500",
        default: "bg-gray-100 text-gray-800 border border-gray-500",
        warning: "bg-yellow-100 text-yellow-800 border border-yellow-500",
        info: "bg-blue-100 text-blue-800 border border-blue-500",
      },
    };
  }
  componentDidMount() {
    if (!this.props.duration) return;
    setTimeout(() => {
      this.props.remove!();
    }, this.props.duration);
  }
  render(): ReactNode {
    return (
      <div
        className={clsx(
          "w-fit h-fit shadow-xl rounded-xl font-interregular text-xs mb-3",
          "p-3 max-w-96",
          this.state.tema[this.props.theme ?? "default"]
        )}
      >
        <div className="flex justify-start gap-x-1">
          <div className="mr-auto ">
            <h1 className="font-intersemibold text-sm">{this.props.title}</h1>
            <div className="mt-1">{this.props.body}</div>
          </div>
          {this.props.onClose && (
            <span className="mb-auto cursor-pointer w-fit h-fit">
              <Icon
                icon="close"
                width={18}
                height={18}
                onClick={(event: any) => {
                  this.props.remove!();
                  this.props.onClose!(event);
                }}
              />
            </span>
          )}
        </div>
      </div>
    );
  }
}

export default Notification;
