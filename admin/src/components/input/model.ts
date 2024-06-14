import React from "react";

type InputSize = "small" | "medium" | "large";
type InputType = "text" | "password" | 'number' | 'textarea' | 'time' | "upload" | "date" | "datetime-local" | "month";
export interface ModelInput {
    defaultValue?: any;
    label: string;
    placeholder?: string;
    size?: InputSize;
    type?: InputType;
    accept?: string;
    filename?: string;
    preview_action?: string;
    preview?: any;
    readonly?: boolean;
    autosize?: boolean;
    className?: string;
    onValueChange?: any;
    isRequired?: boolean;
    description?: string;
    onChange?: React.ChangeEventHandler<HTMLInputElement>;
}