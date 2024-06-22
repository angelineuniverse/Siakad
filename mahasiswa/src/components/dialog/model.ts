import React from "react";

export interface ModelDialog {
    children?: React.ReactNode;
    onOpen: boolean | React.MouseEventHandler<HTMLDivElement> ;
    onClose: React.MouseEventHandler<HTMLDivElement>;
    className?: string;
    size?: any;
    useHeading?: boolean;
    hideIconClose?: boolean;
    classHeading?: string;
    classTitle?: string;
    title?: string;
    subtitle?: string;
}