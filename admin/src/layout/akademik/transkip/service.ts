import client from "../../../service/service";

export async function index(params: undefined) {
    return await client.get('transkip', params);
}
export async function create(params: undefined) {
    return await client.get('transkip/create', params);
}
export async function store(data: any) {
    return await client.post('transkip', data);
}
export async function edit(id: any) {
    return await client.get('transkip/' + id + '/edit');
}
export async function deleted(id: number) {
    return await client.delete('transkip/' + id);
}
export async function update(id: string | undefined, data: any) {
    return await client.post('transkip/'+id+'/update', data);
}