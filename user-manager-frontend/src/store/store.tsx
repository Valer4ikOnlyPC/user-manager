import {configureStore} from "@reduxjs/toolkit";
import {authenticationApi} from "./authentication-api";
import {usersApi} from "./users-api";

export const store = configureStore({
    reducer: {
        [authenticationApi.reducerPath]: authenticationApi.reducer,
        [usersApi.reducerPath]: usersApi.reducer
    },
    middleware:
        (getDefaultMiddleware) =>
            getDefaultMiddleware()
                .concat(authenticationApi.middleware)
                .concat(usersApi.middleware)
})
