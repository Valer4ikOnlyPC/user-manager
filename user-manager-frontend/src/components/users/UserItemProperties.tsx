import React, {useContext, useEffect, useState} from "react";
import {IUser, IUserName} from "../../models";
import {useDeleteUserMutation, useUpdateUserMutation} from "../../store/users-api";
import {Error} from "../Error";
import {AuthenticationResponse} from "../../store/authentication-api";
import AuthContext from "../../context/AuthContext";

export interface UserItemPropertiesProps {
    user: IUser
    deleteUser: () => void
    loading: (value: boolean) => void
    closeModal: () => void
}

export function UserItemProperties({user, deleteUser: deleteUserCallback, loading, closeModal}: UserItemPropertiesProps) {
    const [deleteUser, {isLoading: isDeleting, error: deleteError}] = useDeleteUserMutation()
    const [updateUser, {data, isLoading: isUpdating, error: updateError}] = useUpdateUserMutation()

    const [error, setError] = useState('');
    const [isSuccessfully, setSuccessfully] = useState<boolean>(false)
    const [firstName, setFirstName] = useState<string>(user.name.first_name)
    const [secondName, setSecondName] = useState<string>(user.name.second_name)
    const [lastName, setLastName] = useState<string>(user.name.last_name ? user.name.last_name : '')
    const [isAdmin, setIsAdmin] = useState<boolean>(user.is_admin);
    const [isAdminLoc, setIsAdminLoc] = useState(true);

    useEffect(() => {
        setFirstName(user.name.first_name)
        setSecondName(user.name.second_name)
        setLastName(user.name.last_name ? user.name.last_name : '')
        setIsAdmin(user.is_admin)
        const isAdminLocHandler = () => {
            let thisUser = localStorage.getItem('user');
            if (!thisUser) return;
            setIsAdminLoc(!((JSON.parse(thisUser) as AuthenticationResponse).user.is_admin && (JSON.parse(thisUser) as AuthenticationResponse).user.id !== user.id));
        }
        isAdminLocHandler()
    }, [user]);

    useEffect(() => {
        setError(JSON.stringify(deleteError) !== '' ? JSON.stringify(deleteError) : error)
        setError(JSON.stringify(updateError) !== '' ? JSON.stringify(updateError) : error)
    }, [deleteError, updateError]);
    useEffect(() => {
        loading(isDeleting || isUpdating)
    }, [isDeleting, isUpdating]);
    useEffect(() => {
        setSuccessfully(false)
    }, [isDeleting, isUpdating, user.id])

    const updateUserHandler = () => {
        setError('');
        let userName = {
            first_name: firstName,
            second_name: secondName,
            last_name: lastName === '' ? null : lastName,
        } as IUserName

        updateUser({user_id: user.id, name: userName, is_admin: isAdmin}).then((value) => {
            if (value && value.hasOwnProperty('data')) {
                setSuccessfully(true)
            }
        })
    }

    const isAdminHandler = () => {
        if (!isAdminLoc) setIsAdmin(!isAdmin);
    }

    const { currentUser, setCurrentUser } = useContext(AuthContext);
    const deleteRuleHandler = () => {
        setError('');
        deleteUser(user.id).then(() => {
            localStorage.removeItem('user');
            setCurrentUser(null)
        })
        deleteUserCallback()
        closeModal()
    }

    const copyUserID = () => {
        let textField = document.createElement('textarea')
        textField.innerText = user.id
        document.body.appendChild(textField)
        textField.select()
        document.execCommand('copy')
        textField.remove()
    };

    const [isAlert, setAlert] = useState(false);
    useEffect(() => {
        setAlert(!!(isSuccessfully || error));
    }, [isSuccessfully, error]);

    const validation = (e: React.ChangeEvent<HTMLInputElement>): string => {
        let text = e.target.value.replace(/[^a-zA-Z-а-яА-Я.]/g, '');
        return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
    }

    return (
        <div
            className="px-4 pt-2 relative pb-1 mb-1 h-full bg-white overflow-hidden">
            <div className={'absolute top-0 bottom-0'}>
                <span
                    className={"bg-white flex items-center text-black p-3 z-10 break-words rounded drop-shadow-lg sticky top-5 w-full transition-opacity " + (isAlert ? 'visible' : 'hidden')}>
                    <div className={'w-full break-words'}>
                        {isSuccessfully &&
                            <p className={'text-center text-green-500'}>Пользователь успешно обновлён.</p>}
                        {error && <Error error={error}/>}
                    </div>
                    <div className={'px-3'}>
                        <button
                            className={'text-center align-sub'}
                            type={'button'}
                            onClick={() => {
                                setAlert(false)
                            }}>
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19"
                                 stroke="black" strokeWidth="3"
                                 className="stroke-gray-300 hover:stroke-gray-400"><line
                                x1="4" x2="15" y1="4" y2="15"></line><line x1="15" x2="4" y1="4" y2="15"></line></svg>
                        </button>
                    </div>
                </span>
            </div>
            <div className={'relative mb-4'}>
                <div className={"mb-2 flex justify-between items-center"}>
                    <label htmlFor="url-shortener" className={"text-sm font-bold text-gray-700 block"}>ID
                        правила</label>
                </div>
                <div className={"flex items-center"}>
                    <div className={"relative w-full"}>
                        <input id="url-shortener" type="text" aria-describedby="helper-text-explanation"
                               className={'appearance-none border border-e-0 text-gray-500 rounded-s-lg w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline'}
                               value={user.id} readOnly={true} onChange={() => {
                        }}/>
                    </div>
                    <button data-tooltip-target="tooltip-url-shortener" data-copy-to-clipboard-target="url-shortener"
                            className={"appearance-none flex-shrink-0 z-1 inline-flex items-center py-2.5 px-4 text-sm font-medium text-center text-gray-500 hover:text-gray-900 bg-gray-100 border border-gray-300 rounded-e-lg hover:bg-gray-200 focus:ring-4 focus:outline-none focus:ring-gray-100"}
                            type="button" onClick={() => {
                        copyUserID()
                    }}>
                        <span id="default-icon"><svg className="w-4 h-4" aria-hidden="true"
                                                     xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                                     viewBox="0 0 18 20">
                            <path
                                d="M16 1h-3.278A1.992 1.992 0 0 0 11 0H7a1.993 1.993 0 0 0-1.722 1H2a2 2 0 0 0-2 2v15a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2Zm-3 14H5a1 1 0 0 1 0-2h8a1 1 0 0 1 0 2Zm0-4H5a1 1 0 0 1 0-2h8a1 1 0 1 1 0 2Zm0-5H5a1 1 0 0 1 0-2h2V2h4v2h2a1 1 0 1 1 0 2Z"/>
                        </svg></span>

                    </button>
                </div>
            </div>
            <div className="relative mb-4">
                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="login">
                    Логин
                </label>
                <input
                    value={user.login}
                    onChange={() => {
                    }}
                    readOnly={true}
                    className="appearance-none border rounded w-full py-2 px-3 text-gray-500 leading-tight focus:outline-none focus:shadow-outline"
                    id="login" type="text" placeholder="Логин"/>
            </div>
            <div className="relative mb-4">
                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="firstName">
                    Фамилия
                </label>
                <input
                    value={firstName ?? ''}
                    onChange={(e) => setFirstName(validation(e))}
                    className="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                    id="firstName" type="text" placeholder="Фамилия"/>
            </div>
            <div className="relative mb-4">
                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="secondName">
                    Имя
                </label>
                <input
                    value={secondName ?? ''}
                    onChange={(e) => setSecondName(validation(e))}
                    className="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                    id="secondName" type="text" placeholder="Имя"/>
            </div>
            <div className="relative mb-4">
                <label className="block text-gray-700 text-sm font-bold mb-2" htmlFor="lastName">
                    Отчетсво
                </label>
                <input
                    value={lastName}
                    onChange={(e) => setLastName(validation(e))}
                    className="border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none"
                    id="lastName" type="text" placeholder="Отчетсво"/>
            </div>
            <div className="relative flex items-start mb-5">
                <div className="flex items-center h-5">
                    <input id="isAdmin" type="checkbox" value="" checked={isAdmin} disabled={isAdminLoc}
                           onChange={isAdminHandler}
                           className="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-blue-300"/>
                </div>
                <label htmlFor="isAdmin"
                       className="ms-2 text-sm font-medium select-none text-gray-900">Администратор</label>
            </div>
            <div className={'mt-2 relative mb-5 text-end text-gray-600'}>
                <p>Дата обновления: <b>{(new Date(user.update_date)).toLocaleDateString()}</b></p>
            </div>
            <div className={'mb-2 flex relative ' + (!user ? 'mt-5' : '')}>
                <button
                    onClick={updateUserHandler}
                    className="bg-blue-500 w-full hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-0.5"
                    type="submit">
                    Сохранить
                </button>
                <button
                    onClick={deleteRuleHandler}
                    className="bg-blue-500 w-full hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline mx-0.5"
                    type="submit">
                    Удалить
                </button>
            </div>
        </div>
    )
}
