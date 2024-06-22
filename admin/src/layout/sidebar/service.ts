import client from '../../service/service';
export async function show() {
    return await client.get('menu');
}
export async function logout() {
    return await client.get('menu');
}