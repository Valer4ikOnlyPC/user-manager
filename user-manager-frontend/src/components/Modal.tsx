import React, {useEffect} from "react";

interface ModalProps {
    children: React.ReactNode
    title: string
    onClose: () => void
}

export function Modal({children, title, onClose}: ModalProps) {
    const escDownEvent = (event: any) => {
        if(event.key === 'Escape'){
            onClose();
        }
    }
    useEffect(() => {
        window.addEventListener('keydown', escDownEvent);
    }, []);

    return (
        <>
            <div className={'fixed blur bg-black/50 top-0 right-0 left-0 bottom-0 z-50'}/>
            <div id="default-modal"
                 className="overflow-y-auto overflow-x-hidden fixed flex top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full" onMouseDown={onClose}>
                <div className="relative p-4 w-full max-w-2xl max-h-full" onMouseDown={(e) => {e.stopPropagation()}}>
                    <div className="relative p-5 bg-white rounded-lg shadow">
                        <div className={'flex justify-between'}>
                            <h1 className={'text-2xl text-center mb-2'}>{title}</h1>
                            <button onClick={onClose} className={'min-[901px]:hidden'}>
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19"
                                     stroke="black" strokeWidth="3"
                                     className="stroke-gray-300 hover:stroke-gray-400">
                                    <line x1="4" x2="15" y1="4" y2="15"></line>
                                    <line x1="15" x2="4" y1="4" y2="15"></line>
                                </svg>
                            </button>
                        </div>
                        {children}
                    </div>
                </div>
            </div>
        </>
    )
}
