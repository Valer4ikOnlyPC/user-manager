import {createApi, fetchBaseQuery} from "@reduxjs/toolkit/dist/query/react";
import {BaseQueryError} from "@reduxjs/toolkit/dist/query/baseQueryTypes";
import {IUploadPhoto} from "../models";
import {usersApi} from "./users-api";
import {prepareHeadersHandler, responseHandler} from "./functions";

export interface UploadPhotosResponse {
    result: IUploadPhoto,
}

export const photoApi = createApi({
    reducerPath: 'photoApi',
    tagTypes: ['photo'],
    baseQuery: fetchBaseQuery(
        {
            baseUrl: '/api/v1/photo',
            prepareHeaders: prepareHeadersHandler,
            responseHandler: responseHandler,
        },
    ),
    endpoints: (builder) => ({
        uploadPhotos: builder.mutation<UploadPhotosResponse, { photosBase64: string[], user_id: string|null }>({
            query: (arg): any => ({
                url: 'upload',
                method: 'POST',
                body: {
                    "photos": arg.photosBase64,
                    "user_id": arg.user_id
                }
            }),
            transformErrorResponse: (baseQueryReturnValue: BaseQueryError<any>): string => {
                return baseQueryReturnValue.data.error.message
            },
            async onQueryStarted(_, { dispatch, queryFulfilled }) {
                try {
                    await queryFulfilled;
                } catch (e) {}
                dispatch(usersApi.util.invalidateTags([{type: 'Users', id: 'LIST'}]));
            },
        }),
        deletePhoto: builder.mutation<any, { user_id: string, photo_id: string }>({
            query: (arg): any => ({
                url: 'remove',
                method: 'POST',
                body: {
                    "user_id": arg.user_id,
                    "photo_id": arg.photo_id
                }
            }),
            transformErrorResponse: (baseQueryReturnValue: BaseQueryError<any>): string => {
                return baseQueryReturnValue.data.error.message
            },
            async onQueryStarted(_, { dispatch, queryFulfilled }) {
                try {
                    await queryFulfilled;
                } catch (e) {}
                dispatch(usersApi.util.invalidateTags([{type: 'Users', id: 'LIST'}]));
            },
        }),
    })
})

export const {
    useUploadPhotosMutation,
    useDeletePhotoMutation
} = photoApi;
