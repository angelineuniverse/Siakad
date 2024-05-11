import { ResponseColumn } from "../components/table/model";

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