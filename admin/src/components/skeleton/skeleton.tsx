import React, { Component } from "react";
import { ModelSkeleton } from "./model";
import clsx from "clsx";
import "./style.css";

class Skeleton extends Component<ModelSkeleton> {
  render(): React.ReactNode {
    return (
      <div>
        {(() => {
          switch (this.props.type) {
            case "text":
            case "span":
              return (
                <div
                  className={clsx(
                    "skeleton",
                    `skeleton-${this.props.type}`,
                    this.props.className
                  )}
                ></div>
              );
            case "input":
              return (
                <div>
                  <div
                    className={clsx(
                      "skeleton skeleton-label",
                      this.props.className
                    )}
                  ></div>
                  <div
                    className={clsx(
                      "skeleton skeleton-input",
                      this.props.className
                    )}
                  ></div>
                </div>
              );
            case "random":
              return (
                <div
                  className={clsx(
                    "skeleton",
                    "h-[1.1rem] mb-[0.5rem] rounded-[0.25rem]",
                    `w-[${Math.floor(Math.random() * 6) + 1}0%]`
                  )}
                ></div>
              );
            case "custom":
              return (
                <div
                  className={clsx(
                    "skeleton",
                    "mb-[0.5rem] rounded-[0.25rem] w-full",
                    this.props.className
                  )}
                ></div>
              );
            default:
              return null;
          }
        })()}
      </div>
    );
  }
}

export default Skeleton;
