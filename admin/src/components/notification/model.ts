import React from "react";

type NotificationPosition = 'top' | 'top-right' | 'top-left' | 'bottom' | 'bottom-right' | 'bottom-left';
type NotificationTheme = 'success' | 'error' | 'default' | 'warning' | 'info';
export interface ModelNotification{
    key?: string;
    position: NotificationPosition;
    theme: NotificationTheme;
    body: React.ReactNode;
    title: string;
    duration?: number;
    onClose?: (e: React.MouseEventHandler<HTMLOrSVGElement>) => void;
    remove?: () => void;
}