import client from "../../../service/service";
export async function periode_index(params: undefined) {
    return await client.get('krs-periode', params);
}
export async function periode_show(id:string|undefined) {
    return await client.get('krs-periode/'+ id);
}
export async function periode_create() {
    return await client.get('krs-periode/create');
}
export async function periode_store(data: any) {
    return await client.post('krs-periode', data);
}
export async function periode_edit(id: any) {
    return await client.get('krs-periode/' + id + '/edit');
}
export async function periode_deleted(id: number) {
    return await client.delete('krs-periode/' + id);
}
export async function periode_update(id: string | undefined, data: any) {
    return await client.post('krs-periode/'+id+'/update', data);
}
export async function index(params: undefined) {
    return await client.get('krs', params);
}
export async function create(params: undefined) {
    return await client.get('krs/create', params);
}
export async function store(data: any) {
    return await client.post('krs', data);
}
export async function edit(id: any) {
    return await client.get('krs/' + id + '/edit');
}
export async function deleted(id: number) {
    return await client.delete('krs/' + id);
}
export async function update(id: string | undefined, data: any) {
    return await client.post('krs/'+id+'/update', data);
}
export async function formKrs(params: undefined) {
    return await client.get('matakuliah/'+params+'/krs');
}
export async function listMatakuliah(periodeId: string | undefined) {
    return await client.get('krs-periode/' + periodeId + '/matakuliah');
}
export async function selectedMatakuliah(periodeId: string | undefined, mahasiswaId: string | number | undefined) {
    return await client.get('krs-periode/' + periodeId + '/matakuliah/' + mahasiswaId);
}
export async function nilai() {
    return await client.get('nilai');
}
export async function updateNilai(id: number,data: any) {
    return await client.post('krs/'+id+'/nilai',data);
}
export async function updateStatus(id: number) {
    return await client.get('krs/'+id+'/validasi');
}