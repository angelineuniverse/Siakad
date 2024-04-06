export interface ModelForm{
    className?: string;
    classNameLoading?: string;
    form: Array<FormProps>;
}

export interface FormProps {
    key?: string;
    type: string;
    label: string;
    isRequired: boolean;
    readonly?: boolean;
    description?: string;
    placeholder: string;
    list?: ListProps;
}

interface ListProps {
    options: Array<any>,
    keyValue: string,
    keyoption: string;
}