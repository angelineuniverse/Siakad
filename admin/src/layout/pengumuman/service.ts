import client from "../../service/service";

export async function index(params: undefined) {
    return await client.get('pengumuman', params);
}
export async function create(params: undefined) {
    return await client.get('pengumuman/create', params);
}
export async function store(data: any) {
    return await client.post('pengumuman', data);
}
export async function edit(id: string | undefined) {
    return await client.get('pengumuman/' + id + '/edit');
}
export async function deleted(id: number) {
    return await client.delete('pengumuman/' + id);
}
export async function update(id: string | undefined, data: any) {
    return await client.post('pengumuman/'+id+'/update', data);
}