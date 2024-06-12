import React, {useCallback, useState, useRef, useEffect} from 'react';
import {_x, __} from '@wordpress/i18n';
import ItemRow from "@/side-cart/components/ItemRow";
import {useCart, useCartItems} from "@/hooks.js";
import {refreshData, translateJQueryEventToNative} from "@/helpers.js";
import { useClickAway } from "@uidotdev/usehooks";

function App() {
    const [isOpen, setIsOpen] = useState(false)
    const items = useCartItems();
    const cart = useCart();


    const ref = useClickAway(() => {
        setIsOpen(false);
    });

    useEffect(() => {
        const removeJQueryAddedToCartEvent = translateJQueryEventToNative(
            'added_to_cart',
            'wc-blocks_added_to_cart'
        );


        const open = function(event) {
            refreshData();
            setIsOpen(true)
        };
        document.body.addEventListener(
            'wc-blocks_added_to_cart',
            open
        );
        return () => {
            removeJQueryAddedToCartEvent();
            document.body.removeEventListener(
                'wc-blocks_added_to_cart',
                open
            );
        };
    }, []);
    return <div className="relative z-[1000]" ref={ref}>
        <div id="drawer-example"
             className={
                 `fixed top-0 right-0 z-40 h-screen p-4 overflow-y-auto transition-transform bg-white w-80 dark:bg-gray-800 ${!isOpen ? 'transform translate-x-full' : 'transform translate-x-0'}`
             }
             tabIndex="-1" aria-labelledby="drawer-label">
            <h5 id="drawer-label"
                className="inline-flex items-center mb-4 text-base font-semibold text-gray-500 dark:text-gray-400">
                <svg className="w-4 h-4 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                     fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                Info
            </h5>
            <button type="button" data-drawer-hide="drawer-example" aria-controls="drawer-example"
                    className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 absolute top-2.5 end-2.5 flex items-center justify-center dark:hover:bg-gray-600 dark:hover:text-white">
                <svg className="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 14 14">
                    <path
                          d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                </svg>
                <span className="sr-only">Close menu</span>
            </button>

            <div>Your cart <span>{cart.itemsCount}</span></div>
            {items.map((item) => {
                return <ItemRow key={item.key} item={item}/>
            })
            }
            <a href="" onClick={() => {
            }}>{__('Continue shopping', 'braasi')}</a>
            <a href={window.side_cart.cart_url}>{__('Go to cart', 'braasi')}</a>
            <a href={window.side_cart.checkout_url}>{__('Checkout', 'braasi')}</a>
        </div>


    </div>
}

export default App;