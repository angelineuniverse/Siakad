import React, { Component, ReactNode, RefObject, createRef } from "react";
import { ModelDialog } from "./model";
import { clsx } from "clsx";
import Icon from "../icon/icon";
import "./style.css";

const SizeDialog: any = {
  small: "w-2/6",
  medium: "w-3/6",
  large: "w-4/6",
  fit: "w-fit",
};

class Dialog extends Component<ModelDialog> {
  modal: RefObject<HTMLDivElement> = createRef();
  constructor(props: ModelDialog) {
    super(props);
    this.modal = createRef();
  }

  render(): ReactNode {
    return (
      <>
        {this.props.onOpen && (
          <div
            ref={this.modal}
            aria-hidden="true"
            className={clsx(
              "py-10",
              "fixed inset-0 flex justify-center items-center",
              "backdrop-blur bg-black/10 transition-all ease-in-out"
            )}
          >
            <div
              className="h-screen w-full absolute z-10"
              aria-hidden="true"
              onClick={this.props.onClose}
            ></div>
            <div
              className={clsx(
                "relative",
                "dialog border z-20 border-gray-100 shadow-2xl rounded-xl bg-white h-fit",
                SizeDialog[this.props.size ?? "fit"] ?? this.props.size,
                this.props.useHeading ? "py-3.5" : "p-5",
                this.props.className
              )}
            >
              {this.props.useHeading && (
                <div className="block sticky top-2">
                  <div className="flex justify-end px-5 mb-5">
                    <div
                      className={clsx(
                        "mr-auto font-interregular text-xsm",
                        this.props.classHeading
                      )}
                    >
                      <h1
                        className={clsx(
                          "font-intersemibold text-lg",
                          this.props.classTitle
                        )}
                      >
                        {this.props.title}
                      </h1>
                      <p>{this.props.subtitle}</p>
                    </div>
                    {!this.props.hideIconClose && (
                      <Icon
                        icon="close"
                        width={25}
                        height={25}
                        color="gray"
                        className="my-auto cursor-pointer"
                        onClick={this.props.onClose}
                      />
                    )}
                  </div>
                  <div className="px-5 max-h-[85vh] overflow-y-auto">
                    {this.props.children}
                  </div>
                </div>
              )}
              {!this.props.useHeading && this.props.children}
            </div>
          </div>
        )}
      </>
    );
  }
}
export default Dialog;
