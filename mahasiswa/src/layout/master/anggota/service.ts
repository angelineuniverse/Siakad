import client from "../../../service/service";

export async function index(params: undefined) {
    return await client.get('admin', params);
}
export async function createForm(params: undefined) {
    return await client.get('admin/create', params);
}