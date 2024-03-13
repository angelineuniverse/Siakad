type SizeDialog = "small" | "medium" | "large" | "custom"
export interface ModelDialog {
    children?: React.ReactNode;
    onOpen: boolean;
    onClose: React.MouseEventHandler<HTMLDivElement>;
    className?: string;
    size?: SizeDialog;
    useHeading?: boolean;
    classHeading?: string;
    title?: string;
    subtitle?: string;
}