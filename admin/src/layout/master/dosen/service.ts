import client from "../../../service/service";

export async function index(params: undefined) {
    return await client.get('dosen', params);
}
export async function create(params: undefined) {
    return await client.get('dosen/create', params);
}
export async function store(data: any) {
    return await client.post('dosen', data);
}
export async function edit(id: any) {
    return await client.get('dosen/' + id + '/edit');
}
export async function deleted(id: number) {
    return await client.delete('dosen/' + id);
}
export async function update(id: string | undefined, data: any) {
    return await client.post('dosen/'+id+'/update', data);
}