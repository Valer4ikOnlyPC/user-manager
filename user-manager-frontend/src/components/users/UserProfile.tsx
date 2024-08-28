import React, {useEffect, useState} from "react";
import {useGetUsersQuery} from "../../store/users-api";
import {AuthenticationResponse} from "../../store/authentication-api";
import {PhotosCarousel} from "../photos/PhotosCarousel";

export function UserProfile() {
    const [userID, setUserID] = useState("");
    const {data: response, isLoading, error} = useGetUsersQuery({user_name: null, page: 1, per_page: 50, user_id: userID})
    const [currentUser, setCurrentUser] = useState(response && response.users.length >  0 ? response.users[0] : null)
    useEffect(() => {
        const getStorageUser = ()  => {
            const user = localStorage.getItem('user');
            if (!user) return null;
            setUserID((JSON.parse(user) as AuthenticationResponse).user.id);
        }
        getStorageUser()
    }, []);
    useEffect(() => {
        setCurrentUser(response && response.users.length >  0 ? response.users[0] : null)
    }, [response]);


    return (
        <div className={'m-5'}>
            <div
                className="w-full bg-white border border-gray-200 rounded-lg shadow">
                <div className="flex flex-col items-center pb-10">
                    <PhotosCarousel photos={currentUser ? currentUser.photos : []} isMini={false}/>
                    <h5 className="mb-1 text-xl font-medium text-gray-900">{
                        currentUser?.name.first_name + ' ' + currentUser?.name.second_name + ' ' + currentUser?.name.last_name
                    }</h5>
                    <span className="text-sm text-gray-500">{currentUser?.is_admin ? 'Администратор' : 'Пользователь'}</span>
                </div>
            </div>
        </div>
    )
}
