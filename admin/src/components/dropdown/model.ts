type Direction = "bottom-right" | "bottom-left" | "right" | "left";
export interface ModelDropdown{
    trigger: React.ReactNode;
    children: React.ReactNode;
    direction?: Direction,
}
export interface ModelDropdownItem{
    children: React.ReactNode;
}