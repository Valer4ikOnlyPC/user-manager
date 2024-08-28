import React from "react";
import {IPhoto, IUser} from "../../models";
import {useDeletePhotoMutation} from "../../store/photo-api";

export interface UploadPhotoListProps {
    photos: IPhoto[],
    isDelete: boolean,
    user: IUser|null
}

export function UploadPhotoList({photos, isDelete, user}: UploadPhotoListProps) {
    const [deletePhoto, {isLoading, error}] = useDeletePhotoMutation();

    const photoDeleteHandler = (photoID: string) => {
        if (!user) return;
        deletePhoto({user_id: user.id, photo_id: photoID}).then(() => {

        })
    }

    return (
        <div className="mt-2 p-2 grid grid-cols-5 gap-2">
            {photos.map(photo =>
                <div key={photo.id} className={'h-[w] relative transition-all duration-150 hover:scale-110'}>
                    <img className={"object-cover h-12 md:h-24 w-full rounded-lg"}
                         src={photo.web_dir} alt=""/>
                    {isDelete && <button className={'absolute transition-all duration-150 text-gray-600/20 hover:text-gray-600/50 w-full h-full top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 flex justify-center items-center'}
                        onClick={() => photoDeleteHandler(photo.id)}>
                        <svg className="w-10 h-10" aria-hidden="true"
                             xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="3"
                                  d="M6 18 17.94 6M18 18 6.06 6"/>
                        </svg>
                    </button>}
                </div>
            )}
        </div>
    )
}
