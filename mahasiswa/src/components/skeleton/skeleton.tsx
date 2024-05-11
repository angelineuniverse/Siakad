import React, { Component, ReactNode } from "react";
import { ModelSkeleton } from "./model";
import clsx from "clsx";
import "./style.css";

class Skeleton extends Component<ModelSkeleton> {
  render(): ReactNode {
    return (
      <div>
        {(() => {
          switch (this.props.type) {
            case "text":
            case "span":
              return (
                <div
                  className={clsx("skeleton", `skeleton-${this.props.type}`)}
                ></div>
              );
            case "input":
              return (
                <div>
                  <div className={clsx("skeleton skeleton-label")}></div>
                  <div className={clsx("skeleton skeleton-input")}></div>
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
            default:
              return null;
          }
        })()}
      </div>
    );
  }
}

export default Skeleton;
