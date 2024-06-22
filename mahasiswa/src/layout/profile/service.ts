import client from "../../service/service";

export async function index() {
    return await client.get('mahasiswa/profile/form');
}
export async function update(data: any) {
    return await client.post('mahasiswa/update/profile', data);
}