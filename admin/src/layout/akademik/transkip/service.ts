import client from "../../../service/service";
export async function periode_index(params: undefined) {
    return await client.get('mahasiswa-periode', params);
}
export async function periode_show(id:string|undefined) {
    return await client.get('mahasiswa-periode/'+ id);
}
export async function periode_create() {
    return await client.get('mahasiswa-periode/create');
}
export async function periode_store(data: any) {
    return await client.post('mahasiswa-periode', data);
}
export async function periode_edit(id: any) {
    return await client.get('mahasiswa-periode/' + id + '/edit');
}
export async function periode_deleted(id: number) {
    return await client.delete('mahasiswa-periode/' + id);
}
export async function periode_update(id: string | undefined, data: any) {
    return await client.post('mahasiswa-periode/'+id+'/update', data);
}
export async function index(params: undefined) {
    return await client.get('mahasiswa', params);
}
export async function create(params: undefined) {
    return await client.get('mahasiswa/create', params);
}
export async function store(data: any) {
    return await client.post('mahasiswa', data);
}
export async function edit(id: any) {
    return await client.get('mahasiswa/' + id + '/edit');
}
export async function deleted(id: number) {
    return await client.delete('mahasiswa/' + id);
}
export async function update(id: string | undefined, data: any) {
    return await client.post('mahasiswa/'+id+'/update', data);
}