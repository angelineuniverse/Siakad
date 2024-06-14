import client from "../../../service/service";

export async function index(params: undefined) {
    return await client.get('matakuliah', params);
}
export async function create(params: undefined) {
    return await client.get('matakuliah/create', params);
}
export async function store(data: any) {
    return await client.post('matakuliah', data);
}
export async function edit(id: any) {
    return await client.get('matakuliah/' + id + '/edit');
}
export async function deleted(id: number) {
    return await client.delete('matakuliah/' + id);
}
export async function update(id: string | undefined, data: any) {
    return await client.post('matakuliah/'+id+'/update', data);
}