export interface ModelTable{
    className?: string;
    useCreate: boolean;
    skeletonRow?: number;
    create?: React.MouseEventHandler<HTMLButtonElement>;
    column: Array<ResponseColumn> | any;
    property?: Object;
    data?: Array<any>;
    delete?: (e: any) => void;
    add?: (e: any) => void;
    show?: (e: any) => void;
    edit?: (e: any) => void;
}

type TypeColumn = "string" | "datetime" | "object" | "array" | "action" | "date" | 'status';
export interface ResponseColumn{
    key: string;
    name?: string;
    type: TypeColumn;
    child?: Array<ResponseColumn>;
    ability?: Array<string>
    className?: string;
    color?: string;
}