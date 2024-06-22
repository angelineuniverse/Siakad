import client from "../../service/service";

export async function me() {
    return await client.get('mahasiswa/profile/detail');
}
export async function jadwal() {
    return await client.get('krs/me');
}
export async function tagihan() {
    return await client.get('finance/mahasiswa/bayaran');
}
export async function ipk() {
    return await client.get('matakuliah/ipk/final');
}