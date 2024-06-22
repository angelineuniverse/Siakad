import client from '../../service/service';
export async function login(data: any) {
    return await client.post('mahasiswa/login',data);
}
export async function logout() {
    return await client.get('mahasiswa/auth/logout');
}
export async function previewFile(url: string, folder: string, filename: string) {
    return await client.get(url, {
        params: {
            folder: folder,
            filename: filename
        },
        responseType: "blob",
    });
}