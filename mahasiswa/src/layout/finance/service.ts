import client from "../../service/service";

export async function index() {
    return await client.get('finance/mahasiswa/history/bayaran');
}