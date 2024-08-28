import React, {useEffect, useState} from "react";
import {IPhoto} from "../../models";

export interface PhotosCarouselProps {
    photos: IPhoto[],
    isMini: boolean,
}

export function PhotosCarousel({photos, isMini}: PhotosCarouselProps) {
    const [selectedPhotoIndex, setSelectedPhotoIndex] = useState<number>(0);
    const [selectedPhoto, setSelectedPhoto] = useState<IPhoto>(photos[selectedPhotoIndex]);
    useEffect(() => {
        setSelectedPhotoIndex(0)
        setSelectedPhoto(photos[selectedPhotoIndex])
    }, [photos]);
    useEffect(() => {
        setSelectedPhoto(photos[selectedPhotoIndex])
    }, [selectedPhotoIndex]);
    const handlePhotoButtonClick = (e: React.MouseEvent<HTMLButtonElement, MouseEvent>, action: string = 'prev') => {
        e.stopPropagation()
        switch (action) {
            case 'prev':
                setSelectedPhotoIndex(selectedPhotoIndex > 0 ? selectedPhotoIndex - 1 : photos.length - 1);
                break;
            case 'next':
                setSelectedPhotoIndex(photos.length - 1 > selectedPhotoIndex ? selectedPhotoIndex + 1 : 0);
                break;
        }
    }

    const handleDownBottomClick = (e: React.MouseEvent<HTMLButtonElement, MouseEvent>, index: number) => {
        e.stopPropagation()
        setSelectedPhotoIndex(index)
    }

    return (
        <div id="indicators-carousel" className="relative w-full carousel">
            <div className={"relative overflow-hidden rounded-lg " + (isMini ? 'h-44 md:h-56': 'h-56 md:h-72')}>
                {photos.map(photo =>
                    <div key={photo.id}
                         className={'duration-700 ease-in-out ' + (photo === selectedPhoto ? '' : 'hidden')}>
                        <div
                            className={'absolute block bg-cover w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2'}
                            style={{backgroundImage: `url(${photo.web_dir})`, filter: 'blur(2px)'}}
                        ></div>
                        <img src={photo.web_dir}
                             className={'absolute block w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 ' + (isMini ? 'object-cover' : 'object-contain')}
                             alt={photo.web_dir}/>
                    </div>
                )}
                {photos.length === 0 &&
                    <div className={'duration-700 ease-in-out'}>
                        <div
                            className={'absolute block bg-cover w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2'}
                            style={{backgroundImage: `url(/photos/default.png)`, filter: 'blur(2px)'}}
                        ></div>
                        <img src={'/photos/default.png'}
                             className={'absolute block object-contain w-full h-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2'}
                             alt={'/photos/default.png'}/>
                    </div>}
            </div>
            <div className="absolute carousel-elem bg-gray-900/20 rounded-md z-30 flex -translate-x-1/2 space-x-3 rtl:space-x-reverse bottom-2 left-1/2">
                {!isMini && photos.map((photo, index) =>
                    <button key={photo.id} type="button" className={'w-3 h-3 rounded-full  hover:bg-white/50 ' + (photo === selectedPhoto ? 'bg-white' : 'bg-white/30')} aria-current="true"
                            aria-label={photo.web_dir}
                            onClick={(e) => handleDownBottomClick(e, index)}></button>
                )}
            </div>
            {photos.length > 1 && <>
                <button type="button"
                    className={"absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 rounded-l-lg cursor-pointer group focus:outline-none " + (isMini ? '' : 'bg-gray-900/20')}
                    onClick={(e) => handlePhotoButtonClick(e)}>
                    <span className="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50">
                        <svg className="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 1 1 5l4 4"/>
                        </svg>
                        <span className="sr-only">Previous</span>
                    </span>
                </button>
                <button type="button" className={"absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 rounded-r-lg cursor-pointer group focus:outline-none " + (isMini ? '' : 'bg-gray-900/20')}
                    onClick={(e) => handlePhotoButtonClick(e, 'next')}>
                    <span className="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 group-hover:bg-white/50">
                        <svg className="w-4 h-4 text-white rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span className="sr-only">Next</span>
                    </span>
                </button>
            </>}
        </div>
    )
}
