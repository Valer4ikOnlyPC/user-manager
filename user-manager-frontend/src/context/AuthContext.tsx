import React, {createContext, useEffect, useState} from "react";
import {Login} from "../pages/Login";
import {AuthenticationResponse} from "../store/authentication-api";

interface IAuthContext {
    currentUser: AuthenticationResponse|null,
    setCurrentUser: (value: AuthenticationResponse|null) => void
}

export const AuthContext = createContext<IAuthContext>({
    currentUser: null,
    setCurrentUser: (value: AuthenticationResponse|null) => {}
})

export const AuthContextProvider = ({children}: { children: React.ReactNode }) => {
    const [ currentUser, setCurrentUser ] = useState<AuthenticationResponse|null>(null);

    useEffect(() => {
        const checkLoggedIn = async () => {
            let currentUser = isAuthenticated();
            if (currentUser === null) {
                localStorage.setItem('user', '');
                currentUser = null;
            }

            setCurrentUser(currentUser);
        };

        checkLoggedIn();
    }, []);

    return (
        <AuthContext.Provider value={{ currentUser: currentUser, setCurrentUser: setCurrentUser }}>
            { currentUser?.token ? children : <Login/>}
         </AuthContext.Provider>
    )
};

export default AuthContext;

const isAuthenticated = (): AuthenticationResponse|null => {
    const user = localStorage.getItem('user');
    if (!user) {
        return null
    }
    return JSON.parse(user) as AuthenticationResponse
};
