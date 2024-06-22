import client from "../../../service/service";

export async function index(params: undefined) {
    return await client.get('admin', params);
}
export async function createForm(params: undefined) {
    return await client.get('admin/create', params);
}
export async function store(data: any) {
    return await client.post('admin', data);
}
export async function edit(id: number) {
    return await client.get('admin/' + id + '/edit');
}
export async function deleted(id: number) {
    return await client.delete('admin/' + id);
}
export async function update(id: number, data: any) {
    return await client.post('admin/'+id+'/update', data);
}