import { ModelNotification } from "./model";
import React from "react";
import Notification from "./notification";
import ReactDOM from "react-dom/client";
import clsx from "clsx";
import "./style.css";
export class NotificationManager {
  private containerRef: HTMLDivElement;
  private position = {
    top: "top-5 right-0 left-0",
    "top-right": "top-5 right-5",
    "top-left": "top-5 left-5",
    bottom: "bottom-5 right-0 left-0",
    "bottom-right": "bottom-5 right-5",
    "bottom-left": "bottom-5 left-5",
  };

  constructor() {
    const body = document.getElementsByTagName("body")[0] as HTMLBodyElement;
    const toastContainer = document.createElement("div") as HTMLDivElement;
    toastContainer.id = "toast-container-main";
    body.insertAdjacentElement("beforeend", toastContainer);
    this.containerRef = toastContainer;
  }
  public show(model: ModelNotification): void {
    let containerNotif = document.createElement("div");
    model.key = Math.random().toString(36).substring(2, 9);
    containerNotif.setAttribute("id", model.key);
    this.containerRef.className = clsx(
      "w-fit h-fit fixed",
      this.position[model.position ?? "top-right"]
    );
    this.containerRef.appendChild(containerNotif);
    const root = ReactDOM.createRoot(containerNotif);
    root.render(
      <Notification {...model} remove={() => this.destroy(containerNotif.id)} />
    );
  }

  public destroy(documentId: string): void {
    let container = document.getElementById(documentId) as Element;
    container.remove();
  }
}

export const notification = new NotificationManager();
