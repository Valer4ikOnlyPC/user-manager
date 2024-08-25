import React, {useContext, useState} from 'react';
import {Link, Route, Routes} from "react-router-dom";
import {HomePage} from "./pages/HomePage";
import {NotFoundPage} from "./pages/NotFoundPage";
import AuthContext from "./context/AuthContext";
import {UsersPage} from "./pages/UsersPage";

function App() {
    const { currentUser, setCurrentUser } = useContext(AuthContext);
    const handleLogOut = () => {
        localStorage.removeItem('user');
        setCurrentUser(null);
    };

    const [navbarState, setNavbarState] = useState<boolean>(true)

    return (
      <div className={'flex justify-center'}>
          <nav className="bg-white fixed w-full z-20 top-0 start-0 border-b border-gray-200">
              <div className="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
                  <Link to={'/'} className={'flex items-center cursor-pointer select-none space-x-3 rtl:space-x-reverse'}>
                      <span className="self-center text-2xl text-gray-800 font-semibold whitespace-nowrap">User-manager</span>
                  </Link>
                  <div className="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                      <button data-collapse-toggle="navbar-sticky" type="button"
                              className="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200"
                              onClick={() => {setNavbarState(!navbarState)}}>
                          <span className="sr-only">Open main menu</span>
                          <svg className="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                               viewBox="0 0 17 14">
                              <path stroke="currentColor" strokeLinecap="round" strokeLinejoin="round"
                                    strokeWidth="2" d="M1 1h15M1 7h15M1 13h15"/>
                          </svg>
                      </button>
                  </div>
                  <div className={'items-center justify-between w-full md:flex md:w-auto md:order-1 ' + (navbarState ? 'hidden' : '')}
                       id="navbar-sticky">
                      <ul className="flex flex-col p-4 md:p-0 mt-4 font-medium rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white">
                          <li>
                              <Link to={'/users'}
                                    className={'block py-2 px-3 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0'}>
                                  {'Пользователи'}
                              </Link>
                          </li>
                          <li>
                            <a className={'block py-2 px-3 text-gray-700 rounded cursor-pointer hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0'}
                                onClick={handleLogOut}>
                                {'Выйти'}
                            </a>
                          </li>
                      </ul>
                  </div>
              </div>
          </nav>
          <div className={'w-full mt-20 md:w-auto'}>
              <Routes>
                  <Route path={'/'} element={<HomePage/>}/>
                  <Route path={'/users'} element={<UsersPage/>}/>
                  <Route path={'/*'} element={<NotFoundPage/>}/>
              </Routes>
          </div>
      </div>
  );
}

export default App;
