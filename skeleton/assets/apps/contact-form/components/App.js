import React, { useCallback, useState, useRef } from 'react';
import { apiFetch } from '../../helpers';
import { _x, __ } from '@wordpress/i18n';

export const CONTACT_FORM_OK = 'CONTACT_FORM_OK';
export const CONTACT_FORM_ERROR = 'CONTACT_FORM_ERROR';
export const CONTACT_FORM_WAITING = 'CONTACT_FORM_WAITING';
export const CONTACT_FORM_NEW = 'CONTACT_FORM_NEW';

function App ({
  title,
  cta,
  consent,
  api,
  success_message = _x('Thank you for contacting us, form was successfully sent.', 'contact form', 'wpify-skeleton'),
  error_message = _x('The message was not sent due an error, please contact us via email', 'contact form', 'wpify-skeleton'),
}) {
  const [result, setResult] = useState(CONTACT_FORM_NEW);
  const formRef = useRef();

  const handleOk = useCallback(() => {
    setResult(CONTACT_FORM_OK);
    formRef.current.reset();
  }, [setResult]);

  const handleError = useCallback(() => {
    setResult(CONTACT_FORM_ERROR);
  }, [setResult]);

  const handleSubmit = useCallback(function (event) {
    event.preventDefault();

    const data = [...event.target.elements].reduce((values, element) => (element.name ? {
      ...values, [element.name]: element.value,
    } : values), {});

    setResult(CONTACT_FORM_WAITING);

    apiFetch({
      url: event.target.action,
      method: 'POST',
      data,
    }).then(handleOk).catch(handleError);
  }, [handleOk, handleError, setResult]);

  const handleDismiss = useCallback(function () {
    setResult(CONTACT_FORM_NEW);
  }, []);

  const honeypotStyle = {
    position: 'fixed', top: '-1000px', left: '-1000px',
  };

  return (
    <div className="contact">
      <h2 className="title contact__title">{title}</h2>
      <form action={api} className="form" method="POST" ref={formRef} onSubmit={handleSubmit}>
        {[CONTACT_FORM_OK, CONTACT_FORM_ERROR].includes(result) && (
          <div className="contact__message" onClick={handleDismiss}>
            {result === CONTACT_FORM_OK && success_message && (
              <div className="contact__message-item contact__message-item--success" dangerouslySetInnerHTML={{ __html: success_message }} />
            )}
            {result === CONTACT_FORM_ERROR && error_message && (
              <div className="contact__message-item contact__message-item--error" dangerouslySetInnerHTML={{ __html: error_message }} />
            )}
          </div>
        )}
        <div className="form__row" style={honeypotStyle}>
          <div className="form__item">
            <label htmlFor="name">
              {__('Your first name', 'wpify-skeleton')} *
            </label>
            <input
              type="text"
              id="firstname"
              name="firstname"
              tabIndex={-1}
              placeholder={_x('Your first name', 'contact form', 'wpify-skeleton')}
            />
          </div>
        </div>
        <div className="form__row">
          <div className="form__item">
            <label htmlFor="name">
              {__('Your name', 'wpify-skeleton')} *
            </label>
            <input
              name="name"
              type="text"
              id="name"
              required
              placeholder=" "
            />
          </div>
          <div className="form__item">
            <label htmlFor="company">
              {_x('Your company / Organization', 'contact form', 'wpify-skeleton')}
            </label>
            <input
              name="company"
              type="text"
              id="company"
            />
          </div>
        </div>
        <div className="form__row">
          <div className="form__item">
            <label htmlFor="email">
              {__('E-mail', 'wpify-skeleton')} *
            </label>
            <input
              name="email"
              type="email"
              id="email"
              required
            />
          </div>
          <div className="form__item">
            <label htmlFor="phone">
              {__('Phone number', 'wpify-skeleton')}
            </label>
            <input
              name="phone"
              type="text"
              id="phone"
              defaultValue="+420 "
            />
          </div>
        </div>
        <div className="form__item">
          <label htmlFor="message">
            {_x('Message', 'contact form', 'wpify-skeleton')} *
          </label>
          <textarea
            name="message"
            id="message"
            required
          />
        </div>
        <div className="checkbox">
          <label htmlFor="checkbox">
            <input
              type="checkbox"
              id="checkbox"
              name="terms"
              defaultValue="agree"
              required
            />
            <span></span>
            <div dangerouslySetInnerHTML={{ __html: consent }}/>
          </label>
        </div>

        <button className="btn btn--primary contact__btn" type="submit" disabled={result === CONTACT_FORM_WAITING}>
          <span>{cta}</span>
        </button>
      </form>
    </div>
  );
}

export default App;
