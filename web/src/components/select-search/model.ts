import React from "react";

type InputSize = "small" | "medium" | "large";
export interface ModelSelectSearch {
    label: string;
    size?: InputSize;
    className?: string;
    isRequired?: boolean;
    onChange?: React.ChangeEventHandler<HTMLSelectElement>;
    chidlren: React.ReactNode
}

export const sizeLabel = {
    small: "text-xsm",
    medium: "text-sm",
    large: "text-lg",
};

export const sizeInput = {
    small: "text-xsm px-2.5 placeholder:text-xsm",
    medium: "text-sm px-2.5 placeholder:text-sm",
    large: "text-lg px-3 placeholder:text-lg",
  };