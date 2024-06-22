export interface Model{
    className?: string,
    label: string,
    onChange?: React.ChangeEventHandler<HTMLInputElement>;
    onValueChange?: any;
    defaultValue?: any;
}