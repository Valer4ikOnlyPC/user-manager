import React, {useEffect, useState} from "react";

interface PaginationProps{
    initPage: number
    initPageSize: number
    allCount: number
    onSelectPageHandler: (page: number) => void
    onSelectPageSizeHandler: (pageSize: number) => void
}

export function Pagination(
    {initPage, initPageSize, allCount, onSelectPageHandler, onSelectPageSizeHandler}: PaginationProps
) {
    const [page, setPage] = useState<number>(Number(initPage))
    const [pageSize, setPageSize] = useState<number>(Number(initPageSize))

    useEffect(() => {
        setPageHandler(initPage)
        setPageSizeHandler(initPageSize)
    }, [initPage, initPageSize]);

    const pageSizeChangeHandler = (event: React.ChangeEvent<HTMLInputElement>) => {
        const size = Number(event.target.value)
        if (size > 0) {
            setPageSizeHandler(size)
        } else {
            setPageSizeHandler(1)
        }
        setPageHandler(1)
    }

    const setPageSizeHandler = (pageSize: number) => {
        setPageSize(pageSize)
        onSelectPageSizeHandler(pageSize)
    }
    const setPageHandler = (page: number) => {
        setPage(page)
        onSelectPageHandler(page)
    }

    return (
        <>
            <div className={'relative'}>
                <div className={'mb-2 mt-5'}>
                    <input
                        id={'per-page'}
                        placeholder={'Элементов на странице'}
                        onChange={pageSizeChangeHandler}
                        className={'appearance-none border bg-white rounded w-full py-2 px-3 leading-tight autofill-bg focus:outline-none focus:shadow-outline'}
                        type={'text'} value={pageSize} min={'1'} max={'10000'}/>
                </div>
                <label htmlFor="per-page"
                       className="z-1 absolute text-sm text-gray-400 duration-300 transform -translate-y-4 scale-75 top-1.5 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                    Элементов на странице
                </label>
                <label htmlFor="per-page"
                       className="z-1 absolute text-sm text-gray-400 duration-300 transform -translate-y-4 scale-75 top-10 origin-[0] bg-white px-2 peer-focus:px-2 peer-focus:text-blue-600 peer-placeholder-shown:scale-100 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:top-1/2 peer-focus:top-2 peer-focus:scale-75 peer-focus:-translate-y-4 rtl:peer-focus:translate-x-1/4 rtl:peer-focus:left-auto start-1">
                    Всего: {allCount}
                </label>
            </div>
            <nav className={'flex justify-center'}>
                <ul className="inline-flex -space-x-px flex-col items-center">
                    {(page - 1 >= 0 || Math.ceil(allCount / pageSize) >= page + 1) &&
                        <>
                            <li className={'mb-1 flex'}>
                                {[2, 1].map(pageID => (
                                    page - pageID > 0 && <button key={pageID}
                                         className={'px-3 py-2 my-0.5 leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 ' + (page - pageID - 1 > 0 && page - pageID - 1 === page - 2 ? 'rounded-l-none' : '')}
                                         onClick={() => setPageHandler(page - pageID)}>
                                        {page - pageID}
                                    </button>
                                ))}
                                <button
                                    className={'px-3 py-2 text-blue-600 border border-gray-300 bg-blue-50 hover:bg-blue-100 hover:text-blue-700 ' + (page === 1 ? 'rounded-l-lg' : '') + (Math.ceil(allCount / pageSize) === page || allCount === 0 ? ' rounded-r-lg' : '')}
                                >
                                    {page}
                                </button>
                                {[1, 2].map(pageID => (
                                    Math.ceil(allCount / pageSize) >= page + pageID && <button key={pageID}
                                       className={'px-3 py-2 my-0.5 leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 ' + (Math.ceil(allCount / pageSize) >= page + pageID + 1 && pageID !== 2 ? 'rounded-r-none' : '')}
                                       onClick={() => setPageHandler(page + pageID)}>
                                        {page + pageID}
                                    </button>
                                ))}
                            </li>
                            <li className={''}>
                                {page - 1 > 0 && <button
                                    className={'px-3 py-2 ml-0 select-none leading-tight text-gray-500 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-100 hover:text-gray-700 ' + ((Math.ceil(allCount / pageSize) >= page + 1) ? '' : 'rounded-r-lg')}
                                    onClick={() => setPageHandler(page - 1)}>
                                    Назад
                                </button>}
                                {Math.ceil(allCount / pageSize) >= page + 1 && <button
                                    className={'px-3 py-2 select-none leading-tight text-gray-500 bg-white border border-gray-300 rounded-r-lg hover:bg-gray-100 hover:text-gray-700 ' + ((page - 1 > 0) ? '' : 'rounded-l-lg')}
                                    onClick={() => setPageHandler(page + 1)}>
                                    Вперёд
                                </button>}
                            </li>
                        </>
                    }
                </ul>
            </nav>
        </>
    )
}
