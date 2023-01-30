import React from 'react';
import ReactDOM from 'react-dom';
import App from './components/App';

document.querySelectorAll('[data-app="contact-form"]').forEach(function (root) {
  ReactDOM.render(<App {...JSON.parse(root.dataset.attributes)} />, root);
});
