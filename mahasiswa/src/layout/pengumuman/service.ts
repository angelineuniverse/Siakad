import client from "../../service/service";

export async function index(params: undefined) {
    return await client.get('pengumuman/user/list', params);
}
export async function create(params: undefined) {
    return await client.get('pengumuman/create', params);
}