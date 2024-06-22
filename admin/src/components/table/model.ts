export interface ModelTable{
    classNameTable?: string;
    className?: string;
    useBack?: boolean;
    useCreate: boolean;
    useHeadline: boolean;
    createTitle?: string;
    title?: string;
    description?: string;
    skeletonRow?: number;
    create?: React.MouseEventHandler<HTMLButtonElement>;
    onBack?: React.MouseEventHandler<HTMLOrSVGElement>;
    loadingCreate?: boolean;
    column: Array<ResponseColumn>;
    property?: Object;
    data?: Array<any>;
    delete?: (e: any) => void;
    add?: (e: any) => void;
    show?: (e: any) => void;
    edit?: (e: any) => void;
    onEvent?: (e: any, key: string) => void;
    custom?: any;
    extraHeader?: React.ReactNode
}

type TypeColumn = "string" | "datetime" | "object" | "array" | "currency" | "action" | "date" | 'status' | "custom" | 'action_status' | "date-prefix"| "file" | "date-prefix-custom";
export interface ResponseColumn{
    key: string;
    name?: string;
    type: TypeColumn;
    classNameprefix?: string;
    prefix?: string;
    dateFormat?: string;
    child?: Array<ResponseColumn>;
    ability?: any;
    currency?: string;
    localecurrency?: string;
    minimumFractionDigits?: number;
    className?: string;
    classNameRow?: string;
    color?: string;
}