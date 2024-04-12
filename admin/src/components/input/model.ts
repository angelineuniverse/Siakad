import React from "react";

type InputSize = "small" | "medium" | "large";
type InputType = "text" | "password" | 'email';
export interface ModelInput {
    defaultValue?: string;
    label: string;
    placeholder?: string;
    size?: InputSize;
    type?: InputType;
    readonly?: boolean;
    className?: string;
    onValueChange?: any;
    isRequired?: boolean;
    description?: string;
    onChange?: React.ChangeEventHandler<HTMLInputElement>;
}