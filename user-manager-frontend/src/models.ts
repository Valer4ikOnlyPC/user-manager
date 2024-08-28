export interface IUser {
    id: string,
    login: string,
    name: IUserName,
    update_date: string,
    is_admin: boolean,
    photos: IPhoto[]
}

export interface IUserName {
    first_name: string,
    second_name: string,
    last_name: string|null
}

export interface IPhoto {
    id: string,
    web_dir: string,
}

export interface IUploadPhoto {
    user_id: string,
    photos: IPhoto[]
}
