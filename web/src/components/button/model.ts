import React from 'react';
type ButtonTheme = "primary" | "error" | "warning" | "outline" | "success";
type ButtonSize = "extrasmall"| "small" | "medium" | "large";
type ButtonWidth = "block" | "full";

export interface ModelButton {
    title: string;
    className?: string;
    theme: ButtonTheme;
    size: ButtonSize;
    width: ButtonWidth;
    isLoading?: boolean;
    onClick?: React.MouseEventHandler<HTMLButtonElement>;
}