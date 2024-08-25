export interface IUser {
    id: string,
    login: string,
    name: IUserName,
    update_date: string,
    is_admin: boolean
}

export interface IUserName {
    first_name: string,
    second_name: string,
    last_name: string|null
}
