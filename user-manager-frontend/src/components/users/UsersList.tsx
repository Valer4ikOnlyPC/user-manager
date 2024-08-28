import React, {useEffect, useState} from "react";
import {useSearchParams} from "react-router-dom";
import {useGetUsersQuery} from "../../store/users-api";
import {Error} from "../Error";
import {UserListItem} from "./UserListItem";
import {Pagination} from "../Pagination";
import {IUser} from "../../models";
import {Modal} from "../Modal";
import {UserItemProperties} from "./UserItemProperties";

export function UsersList() {
    const [searchParams, setSearchParams] = useSearchParams()
    const [page, setPage] = useState<number>(Number(searchParams.get("page")) ? Number(searchParams.get("page")) : 1)
    const [perPage, setPerPage] = useState<number>(Number(searchParams.get("per-page")) ? Number(searchParams.get("per-page")) : 50)
    const [userName, setUserLogin] = useState<string>(searchParams.get("user-name") ? String(searchParams.get("user-name")) : '')
    const {data: response, isLoading, error} = useGetUsersQuery({user_name: (userName === '' ? null : userName), page: page, per_page: perPage, user_id: null})

    useEffect(() => {
        setPage(Number(searchParams.get("page")) ? Number(searchParams.get("page")) : 1)
        setPerPage(Number(searchParams.get("per-page")) ? Number(searchParams.get("per-page")) : 50)
        setUserLogin(searchParams.get("user-name") ? String(searchParams.get("user-name")) : '')
    }, [searchParams]);

    const selectPageHandler = (page: number) => {
        searchParams.set('page', String(page))
        setSearchParams(searchParams)
    }
    const selectPerPageHandler = (perPage: number) => {
        searchParams.set('per-page', String(perPage))
        setSearchParams(searchParams)
    }

    const userNameHandler = (event: React.ChangeEvent<HTMLInputElement>): void => {
        selectPageHandler(1)
        selectPerPageHandler(50)
        searchParams.set('user-name', String(event.target.value))
        setSearchParams(searchParams)
    }

    const [selectedUser, setSelectedUser] = useState<IUser|null>(null)
    const [isModal, setModal] = useState<boolean>(false);
    const [menuLoading, setMenuLoading] = useState<boolean>(false)
    const selectUserHandler = (selectedUser: IUser|null): void => {
        setSelectedUser(selectedUser)
        setModal(true)
    }
    useEffect(() => {
        if (isModal && selectedUser && response) {
            let updatedUser = response.users.find((user) => user.id === selectedUser.id)
            setSelectedUser(updatedUser ? updatedUser : selectedUser)
        }
    }, [response]);

    const menuLoadingHandler = (value: boolean) => {
        setMenuLoading(value)
    }

    return (
        <div className={'m-5'}>
            <div className={'content-center mb-5'}>
                <h1 className={'text-2xl'}>Пользователи</h1>
            </div>
            {isModal && selectedUser && <Modal title={'Редактирование пользователя'} onClose={() => setModal(false)}>
                <UserItemProperties user={selectedUser}
                                    deleteUser={() => (selectUserHandler(null))}
                                    loading={menuLoadingHandler}
                                    closeModal={() => setModal(false)}/>
            </Modal>}
            <div className={''}>
                <div className={'mb-2'}>
                    <input
                        placeholder={'ФИО'}
                        onChange={userNameHandler}
                        className={'appearance-none border rounded w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline'}
                        type={'text'} value={userName ?? ''}/>
                </div>
                <div className={'mb-2'}>
                    {error && <Error error={JSON.stringify(error)}/>}
                    <Pagination
                        initPage={page}
                        initPageSize={perPage}
                        allCount={response ? response.count : 0}
                        onSelectPageHandler={selectPageHandler}
                        onSelectPageSizeHandler={selectPerPageHandler}/>
                </div>
                <div className={"grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4"}>
                    {response && response.users.map(user =>
                        <UserListItem user={user} selectUser={selectUserHandler} key={user.id} />
                    )}
                </div>
            </div>
        </div>
    )
}
