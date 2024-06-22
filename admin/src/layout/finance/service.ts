import client from "../../service/service";
export async function periode_index(params: undefined) {
    return await client.get('finance-periode', params);
}
export async function periode_show(id:string|undefined) {
    return await client.get('finance-periode/'+ id);
}
export async function periode_create(periodeId: string | undefined, mahasiswaId: string | number | undefined) {
    return await client.get('finance-periode/create', {
        params: {
            periodeId: periodeId,
            mahasiswaId: mahasiswaId,
        }
    });
}
export async function periode_setujui(id: number,data: any) {
    return await client.post('finance-periode/' + id + '/update', data);
}
export async function index(params: undefined) {
    return await client.get('finance', params);
}
export async function show(id: undefined | string) {
    return await client.get('finance/'+id);
}
export async function store(data: any) {
    return await client.post('finance', data);
}
export async function create() {
    return await client.get('finance/create');
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
export async function tagihan(data: any) {
    return await client.post('finance/updateTagihan', data);
}
export async function tagihan_detail(id: string | undefined) {
    return await client.get('finance/detail/tagihan', {
        params: {
            id: id,
        }
    });
}