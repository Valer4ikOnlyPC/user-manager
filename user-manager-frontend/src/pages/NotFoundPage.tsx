import React from "react";

export function NotFoundPage() {
    return (
         <div className="absolute left-1/2 -translate-x-1/2">
            <div className="flex items-center justify-center min-h-screen bg-white ">
                <div className="flex flex-col">
                    <div className="flex flex-col items-center">
                        <div className="text-indigo-500 font-bold text-7xl">
                            404
                        </div>

                        <div className="font-bold text-3xl xl:text-7xl lg:text-6xl md:text-5xl mt-10">
                            Not Found
                        </div>

                        <div className="text-gray-400 font-medium text-sm md:text-xl lg:text-2xl mt-8">
                            Данная страница не существует.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
