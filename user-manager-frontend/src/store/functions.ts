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
