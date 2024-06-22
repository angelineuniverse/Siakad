export interface ModelForm{
    className?: string;
    classNameLoading?: string;
    form: Array<FormProps>;
    lengthLoading?: number;
    preview_file?: any;
    children?: any;
    remote_change?: any;
}

export interface FormProps {
    key?: string;
    type: string;
    label: string;
    isRequired: boolean;
    readonly?: boolean;
    description?: string;
    placeholder: string;
    autosize: boolean;
    list?: ListProps;
    children_custom?: any;
}

interface ListProps {
    options: Array<any>,
    keyValue: string,
    keyoption: string;
    keyprefix?: string;
}