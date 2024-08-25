import React, {useEffect, useState} from "react";
import {useAuthenticationMutation, useCreateAccountMutation} from "../store/authentication-api";
import {Loader} from "../components/Loader";
import {Error} from "../components/Error";
import {IUserName} from "../models";
export function Login() {
    const [login, setLogin] = useState<string>('');
    const [firstName, setFirstName] = useState<string>('');
    const [secondName, setSecondName] = useState<string>('');
    const [lastName, setLastName] = useState<string>('');
    const [password, setPassword] = useState<string>('');
    const [authentication, {isLoading, error}] = useAuthenticationMutation();
    const [createAccount, {isLoading: isCreating, error: createError}] = useCreateAccountMutation();
    const sendForm = async (event: React.FormEvent) => {
        event.preventDefault()
        try {
            setShowError('')
            const response = isLogin ? await authentication({login, password}).unwrap() :
                await createAccount({login: login, password: password, name:
                        {first_name: firstName, second_name: secondName, last_name: lastName === '' ? null : lastName} as IUserName }).unwrap();
            const token = response.token;
            if (token) {
                localStorage.setItem('user', JSON.stringify(response));
                window.location.href = window.location.pathname;
            }
        } catch (error) {
            setShowError(JSON.stringify(error))
            console.error('error', error);
        }
    }

    const [isAlert, setAlert] = useState<boolean>(false);
    const [showError, setShowError] = useState('');
    const [isLogin, setIsLogin] = useState<boolean>(true);
    useEffect(() => {
        setAlert(!!(error || createError));
    }, [error, createError]);
    const validation = (e: React.ChangeEvent<HTMLInputElement>): string => {
        let text = e.target.value.replace(/[^a-zA-Z-а-яА-Я.]/g, '');
        return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
    }
    const validationLogin = (e: React.ChangeEvent<HTMLInputElement>): string => e.target.value.replace(/[^a-zA-Z-а-яА-Я-0-9-@-_.]/g, '');

    return (
        <>
            <div className={'fixed bg-black/50 top-0 right-0 left-0 bottom-0 z-10'}/>
            <div id="default-modal"
                 className="overflow-y-auto overflow-x-hidden fixed flex top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                <div className="relative p-4 w-full max-w-2xl max-h-full">
                    <div className="relative p-5 bg-white rounded-lg shadow">
                        <div className="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
                            <div className="sm:mx-auto sm:w-full sm:max-w-sm">
                                <h2 className="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 break-words">{isLogin ? "Авторизация" : "Регистрация"}</h2>
                            </div>

                            <div className="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
                                <form className={'relative'} action="#" method="POST" onSubmit={sendForm}>
                                    <div className={'absolute -top-6 bottom-0'}>
                                        <span
                                            className={"bg-white flex items-center text-black p-3 z-10 break-words rounded drop-shadow-lg sticky top-2 w-full transition-opacity " + (isAlert ? 'visible' : 'hidden')}>
                                            <div className={'w-full break-words'}>
                                                {showError && <Error error={showError}/>}
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
                                    <div className={'mb-2 relative'}>
                                        <label htmlFor="login"
                                               className="block text-sm font-medium leading-6 text-gray-900 break-words">Логин</label>
                                        <div className="mt-2">
                                            <input id="login" name="login" type="text" required value={login}
                                                   onChange={(e) => {
                                                       setLogin(validationLogin(e))
                                                   }}
                                                   className="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                        </div>
                                    </div>
                                    <div className={'mb-2 relative'}>
                                        <div className="flex items-center justify-between">
                                            <label htmlFor="password"
                                                   className="block text-sm font-medium leading-6 text-gray-900 break-words">Пароль</label>
                                        </div>
                                        <div className="mt-2">
                                            <input id="password" name="password" type="password"
                                                   autoComplete="current-password"
                                                   required value={password} onChange={(e) => {
                                                setPassword(e.target.value)
                                            }}
                                                   className="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                        </div>
                                    </div>
                                    {!isLogin &&
                                        <>
                                            <div className={'mb-1 relative'}>
                                                <label htmlFor="firstName"
                                                       className="block text-sm font-medium leading-6 text-gray-900 break-words">Фамилия</label>
                                                <div className="mt-2">
                                                    <input id="firstName" name="firstName" type="text" required
                                                           value={firstName}
                                                           onChange={(e) => {
                                                               setFirstName(validation(e))
                                                           }}
                                                           className="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                                </div>
                                            </div>
                                            <div className={'mb-1 relative'}>
                                                <label htmlFor="secondName"
                                                       className="block text-sm font-medium leading-6 text-gray-900 break-words">Имя</label>
                                                <div className="mt-2">
                                                    <input id="secondName" name="secondName" type="text" required
                                                           value={secondName}
                                                           onChange={(e) => {
                                                               setSecondName(validation(e))
                                                           }}
                                                           className="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                                </div>
                                            </div>
                                            <div className={'mb-2 relative'}>
                                                <label htmlFor="lastName"
                                                       className="block text-sm font-medium leading-6 text-gray-900 break-words">Отчество</label>
                                                <div className="mt-2">
                                                    <input id="lastName" name="lastName" type="text" value={lastName}
                                                           onChange={(e) => {
                                                               setLastName(validation(e))
                                                           }}
                                                           className="block w-full rounded-md border-0 py-1.5 px-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                                </div>
                                            </div>
                                        </>
                                    }
                                    <div className={'mb-2 relative'}>
                                        <button type="submit"
                                                className="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                                        focus-visible:outline-indigo-600 break-words">{isLogin ? "Войти" : "Зарегистрироваться"}
                                        </button>
                                    </div>
                                    <div className="relative text-sm font-medium text-gray-500">
                                        {isLogin &&
                                            <>Нет аккаунта?
                                                <a onClick={() => setIsLogin(!isLogin)}
                                                   className="ml-2 text-blue-700 select-none cursor-pointer hover:underline">Создайте
                                                    его</a></>
                                        }{!isLogin &&
                                        <a onClick={() => setIsLogin(!isLogin)} className="ml-2 text-blue-700 select-none cursor-pointer hover:underline">Вернуться к авторизации</a>
                                        }
                                    </div>
                                    <div>
                                        {(isLoading || isCreating) && <Loader/>}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}
