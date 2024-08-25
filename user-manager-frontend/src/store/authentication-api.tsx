import {createApi} from "@reduxjs/toolkit/query/react";
import {fetchBaseQuery} from "@reduxjs/toolkit/dist/query/react";
import {BaseQueryError} from "@reduxjs/toolkit/dist/query/baseQueryTypes";
import {IUser, IUserName} from "../models";

export interface AuthenticationResponse {
    token: string|null,
    user: IUser
}

export const authenticationApi = createApi({
    reducerPath: 'authenticationApi',
    tagTypes: ['authentication'],
    baseQuery: fetchBaseQuery(
        {
            baseUrl: '/api/v1'
        },
    ),
    endpoints: (builder) => ({
        authentication: builder.mutation<AuthenticationResponse, { login: string, password: string }>({
            query: (arg): any => ({
                url: 'login',
                method: 'POST',
                body: {
                    "login": arg.login,
                    "password": arg.password
                }
            }),
            transformErrorResponse: (baseQueryReturnValue: BaseQueryError<any>): string => {
                return baseQueryReturnValue.data.error.message
            },
            invalidatesTags: (result) => [{type: 'authentication', id: 'LIST'}]
        }),
        createAccount: builder.mutation<AuthenticationResponse, { login: string, password: string, name: IUserName }>({
            query: (arg): any => ({
                url: 'create-account',
                method: 'POST',
                body: {
                    "login": arg.login,
                    "password": arg.password,
                    "name": arg.name
                }
            }),
            transformErrorResponse: (baseQueryReturnValue: BaseQueryError<any>): string => {
                return baseQueryReturnValue.data.error.message
            },
            invalidatesTags: (result) => [{type: 'authentication', id: 'LIST'}]
        }),
    })
})

export const {
    useAuthenticationMutation,
    useCreateAccountMutation
} = authenticationApi;
