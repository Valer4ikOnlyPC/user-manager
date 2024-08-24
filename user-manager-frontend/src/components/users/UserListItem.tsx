import React from 'react';
import {IUser} from "../../models";

export interface UserListItemProps {
    user: IUser,
    selectUser: (user: IUser|null) => void
}

export function UserListItem({user, selectUser}: UserListItemProps) {
    return (
        <div className={'select-none cursor-pointer'}>
            <a className="block max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow hover:bg-gray-100" onClick={() => selectUser(user)}>
                <h5 className="mb-2 text-2xl whitespace-nowrap overflow-ellipsis overflow-hidden font-bold tracking-tight text-gray-900">{user.login}</h5>
                <p className="font-normal h-8 whitespace-nowrap overflow-ellipsis overflow-hidden text-gray-700">{user.name.first_name + ' ' + user.name.second_name + ' ' + (user.name.last_name ?? '')}</p>
                <p className="font-normal text-gray-700">
                    Дата
                    обновления: <br/><b>{`${(new Date(user.update_date)).toLocaleDateString()} ${(new Date(user.update_date)).toLocaleTimeString()}`}</b>
                </p>
            </a>
        </div>
    )
}
