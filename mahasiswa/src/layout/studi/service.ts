import client from "../../service/service";

export async function index(id: number) {
    return await client.get('transkip/'+ id);
}
export async function selectForm(params: undefined) {
    return await client.get('transkip/semester/form', params);
}