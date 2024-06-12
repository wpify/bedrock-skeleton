import React from 'react';
import App from './components/App';
import {createRoot} from "react-dom/client";

document.querySelectorAll('[data-app="side-cart"]').forEach(function (el) {
    const root = createRoot(el)
    root.render(<App/>);

});
