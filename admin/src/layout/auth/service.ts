import client from '../../service/service';
export async function me() {
    return await client.get('admin/me');
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