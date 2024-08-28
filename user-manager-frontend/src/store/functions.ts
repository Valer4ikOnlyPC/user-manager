import {AuthenticationResponse} from "./authentication-api";

export const prepareHeadersHandler = (headers: Headers) => {
    const user = localStorage.getItem('user');
    if (!user) {
        return headers
    }
    const parseUser = JSON.parse(user) as AuthenticationResponse
    headers.set('authorization', 'Bearer ' + parseUser.token);
    return headers;
}
export const responseHandler = (response: Response) => {
    if (!response.ok && response.status === 403) {
        localStorage.removeItem('user');
        window.location.href = window.location.pathname;
    }
    return response.json()
}

const toBase64 = (file: File) => new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => resolve(reader.result);
    reader.onerror = reject;
});

export const filesToBase64 = async (files: File[]) => {
    let result: string[] = [];
    try {
        for (const file of files) {
            result.push((await toBase64(file)) as string);
        }
    } catch(error) {
        return [] as string[];
    }
    return result
}
