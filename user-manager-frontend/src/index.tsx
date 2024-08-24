import React from 'react';
import ReactDOM from 'react-dom/client';
import './index.css';
import App from './App';
import {Provider} from "react-redux";
import {BrowserRouter} from "react-router-dom";
import {store} from "./store/store";
import {AuthContextProvider} from "./context/AuthContext";
import {ModalState} from "./context/ModalContext";

const root = ReactDOM.createRoot(
  document.getElementById('root') as HTMLElement
);
root.render(
  <React.StrictMode>
      <Provider store={store}>
          <BrowserRouter>
              <AuthContextProvider>
                  <ModalState>
                      <App/>
                  </ModalState>
              </AuthContextProvider>
          </BrowserRouter>
      </Provider>
  </React.StrictMode>
);
