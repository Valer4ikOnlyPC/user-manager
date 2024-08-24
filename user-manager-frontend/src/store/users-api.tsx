import {createApi} from "@reduxjs/toolkit/query/react";
import {fetchBaseQuery} from "@reduxjs/toolkit/dist/query/react";
import {BaseQueryError} from "@reduxjs/toolkit/dist/query/baseQueryTypes";
import {prepareHeadersHandler, responseHandler} from "./functions";
import {IUser, IUserName} from "../models";

export interface GetUsersResponse {
    users: IUser[],
    count: number
}

export const usersApi = createApi({
    reducerPath: 'usersApi',
    tagTypes: ['Users'],
    baseQuery: fetchBaseQuery(
        {
            baseUrl: '/api/v1/user',
            prepareHeaders: prepareHeadersHandler,
            responseHandler: responseHandler,
        },
    ),
    endpoints: (builder) => ({
        getUsers: builder.query<GetUsersResponse, { user_name: string|null, page: number, per_page: number }>({
            query: (arg) => ({
                url: 'list',
                method: 'POST',
                body: {
                    "user_name": arg.user_name,
                    "page": arg.page,
                    "per_page": arg.per_page
                },
            }),
            transformErrorResponse: (baseQueryReturnValue: BaseQueryError<any>): string => {
                return baseQueryReturnValue.data.error.message
            },
            providesTags: (result) => result
                ? [
                    ...result.users.map(({id}) => ({type: 'Users' as const, id})),
                    {type: 'Users', id: 'LIST'},
                ]
                : [{type: 'Users', id: 'LIST'}],
        }),
        deleteUser: builder.mutation<any, string>({
            query: (id: string): any => ({
                url: 'delete',
                method: 'POST',
                body: {
                    "user_id": id
                }
            }),
            transformErrorResponse: (baseQueryReturnValue: BaseQueryError<any>): string => {
                return baseQueryReturnValue.data.error.message
            },
            invalidatesTags: [{type: 'Users', id: 'LIST'}]
        }),
        updateUser: builder.mutation<any, { user_id: string, name: IUserName }>({
            query: (arg): any => ({
                url: 'update',
                method: 'POST',
                body: {
                    "user_id": arg.user_id,
                    "name": arg.name
                }
            }),
            transformErrorResponse: (baseQueryReturnValue: BaseQueryError<any>): string => {
                return baseQueryReturnValue.data.error.message
            },
            invalidatesTags: [{type: 'Users', id: 'LIST'}]
        }),
    })
})

export const {
    useGetUsersQuery,
    useDeleteUserMutation,
    useUpdateUserMutation
} = usersApi
