import client from "../../service/service";
export async function periode_index(params: undefined) {
    return await client.get('finance-periode', params);
}
export async function periode_show(id:string|undefined) {
    return await client.get('finance-periode/'+ id);
}
export async function periode_create() {
    return await client.get('finance-periode/create');
}
export async function periode_store(data: any) {
    return await client.post('finance-periode', data);
}
export async function periode_edit(id: any) {
    return await client.get('finance-periode/' + id + '/edit');
}
export async function periode_deleted(id: number) {
    return await client.delete('finance-periode/' + id);
}
export async function periode_update(id: string | undefined, data: any) {
    return await client.post('finance-periode/'+id+'/update', data);
}
export async function index(params: undefined) {
    return await client.get('finance', params);
}
export async function create(params: undefined) {
    return await client.get('finance/create', params);
}
export async function store(data: any) {
    return await client.post('finance', data);
}
export async function edit(id: any) {
    return await client.get('finance/' + id + '/edit');
}
export async function deleted(id: number) {
    return await client.delete('finance/' + id);
}
export async function update(id: string | undefined, data: any) {
    return await client.post('finance/'+id+'/update', data);
}
export async function formKrs(params: undefined) {
    return await client.get('matakuliah/'+params+'/finance');
}
export async function listMatakuliah(periodeId: string | undefined) {
    return await client.get('finance-periode/' + periodeId + '/matakuliah');
}
export async function selectedMatakuliah(periodeId: string | undefined, mahasiswaId: string | number | undefined) {
    return await client.get('finance-periode/' + periodeId + '/matakuliah/' + mahasiswaId);
}