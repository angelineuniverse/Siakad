import { ResponseColumn } from "../components/table/model";
import moment from "moment";
export interface ModelResponList {
    property: Object;
    response_column: Array<ResponseColumn>;
    response_data: Array<any>;
    response_filterable: any;
    response_sortable: any;
    response_message: string;
}

export interface ModelRespon {
    response_data: any;
    response_notifikasi: any;
    response_message: string;
}

export function formInputs(form: Array<any>) {
    let formData = new FormData();
    for (const element of form) {
        const item: any = element;
        if (!item[item?.key]) continue;
        if (item[item?.key] instanceof File) {
            formData.append(item?.key, item[item?.key]);
            continue;
        }
        if (item?.type === "date") {
            formData.append(item?.key, moment(item[item?.key]).format("YYYY-MM-DD"));
            continue;
        }
        if (item[item?.key] instanceof Object) {
            formData.append(item?.key, item[item?.key]?.value);
            continue;
        }
        formData.append(item?.key, item[item?.key]);
    }
    return formData;
}

export function formInputsObject(form: any) {
    let formData = new FormData();
    Object.keys(form).forEach((key, index) => {
        formData.append(key, form[key]);
    });
    return formData;
}