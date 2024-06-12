import React from 'react';
import {createRoot} from 'react-dom/client';
import ReactDOM from 'react-dom';
import App from './components/App';

document.querySelectorAll('[data-app="mini-cart"]').forEach(function (el) {
    const root = createRoot(el)
    root.render(<App/>);
});
