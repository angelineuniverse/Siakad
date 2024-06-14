import React from "react";

type InputSize = "small" | "medium" | "large";
export interface ModelSelect {
    label: string;
    size?: InputSize;
    placeholder?: string;
    className?: string;
    isRequired?: boolean;
    onchange?: any;
    onClick?: (e: React.MouseEventHandler<HTMLOptionElement>) => void;
    keyValue: string,
    keyoption: string,
    options: Array<any>
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