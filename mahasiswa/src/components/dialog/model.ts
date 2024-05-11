import React from "react";

type SizeDialog = "small" | "medium" | "large" | "custom"
export interface ModelDialog {
    children?: React.ReactNode;
    onOpen: boolean | React.MouseEventHandler<HTMLDivElement> ;
    onClose: React.MouseEventHandler<HTMLDivElement>;
    className?: string;
    size?: SizeDialog;
    useHeading?: boolean;
    classHeading?: string;
    title?: string;
    subtitle?: string;
}