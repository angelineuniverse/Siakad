import client from "../../service/service";

export async function create(params: undefined) {
    return await client.get('krs/create', params);
}
export async function store(data: any) {
    return await client.post('krs', data);
}
export async function listMatakuliah() {
    return await client.get('krs-periode/matakuliah/last');
}