import client from "../../service/service";
export async function mahasiswaactive() {
    return await client.get('mahasiswa/active/all');
}
export async function mahasiswaLulus() {
    return await client.get('mahasiswa/active/lulus');
}
export async function mahasiswaChart() {
    return await client.get('mahasiswa/terdaftar/chart');
}
export async function mahasiswaactiveList() {
    return await client.get('mahasiswa/active/list');
}
export async function menunggak() {
    return await client.get('finance/menunggak/all');
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