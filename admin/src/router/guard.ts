import { redirect } from "react-router-dom";
import { getCookie } from "typescript-cookie";

export function authNotExist() {
  const token = getCookie("token");
  if (!token) {
    return redirect("/auth");
  }
  return null;
}

export function authExist() {
  const token = getCookie("token");
  if (token) {
    return redirect("/");
  }
  return null;
}