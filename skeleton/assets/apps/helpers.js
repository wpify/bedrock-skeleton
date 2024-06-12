import {dispatch, useSelect, useDispatch} from "@wordpress/data";
export function deepclone(data) {
    return JSON.parse(JSON.stringify(data));
}

export function isObject(data) {
    return typeof data === 'object' && !Array.isArray(data) && data !== null;
}

const { CART_STORE_KEY } = window.wc.wcBlocksData;

export function apiFetch(nextOptions) {
    const {url, path, data, parse = true, ...remainingOptions} = nextOptions;
    let {body, headers = {}} = nextOptions;

    headers['Accept'] = 'application/json, */*;q=0.1';

    if (data) {
        headers['Content-Type'] = 'application/json';
        body = JSON.stringify(data);
    }

    return window.fetch(
        url || path || window.location.href,
        {
            credentials: 'include', ...remainingOptions, body, headers,
        },
    )
        .then(function (response) {
            if (response.status >= 200 && response.status < 300) {
                return response;
            }

            throw response;
        })
        .then(function (response) {
            return response.json();
        })
}

export const dispatchEvent = (
    name,
    {
        bubbles = false,
        cancelable = false,
        element,
        detail = {},
    }
) => {
    if (!CustomEvent) {
        return;
    }
    if (!element) {
        element = document.body;
    }
    const event = new CustomEvent(name, {
        bubbles,
        cancelable,
        detail,
    });
    element.dispatchEvent(event);
};

export const refreshData = ( event ) => {
    const eventDetail = event?.detail;
    if ( ! eventDetail || ! eventDetail.preserveCartData ) {
        dispatch( CART_STORE_KEY ).invalidateResolutionForStore();
    }
};

export const translateJQueryEventToNative = (
    // Name of the jQuery event to listen to.
    jQueryEventName,
    // Name of the native event to dispatch.
    nativeEventName,
    // Whether the event bubbles.
    bubbles = false,
    // Whether the event is cancelable.
    cancelable = false
) => {
    if (typeof jQuery !== 'function') {
        return () => void null;
    }

    const eventDispatcher = () => {
        dispatchEvent(nativeEventName, {bubbles, cancelable});
    };

    jQuery(document).on(jQueryEventName, eventDispatcher);
    return () => jQuery(document).off(jQueryEventName, eventDispatcher);
};