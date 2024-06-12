import { useSelect } from '@wordpress/data';

const { CART_STORE_KEY, CHECKOUT_STORE_KEY } = window.wc.wcBlocksData;

export const useCartItems = () => {
    return useSelect((select) => {
        const store = select(CART_STORE_KEY);
        return store.getCartData().items;
    }, []);
};

export const useCart = () => {
    return useSelect((select) => {
        const store = select(CART_STORE_KEY);
        return store.getCartData();
    }, []);
};

export const useCheckout = () => {
    return useSelect((select) => {
        const store = select(CHECKOUT_STORE_KEY);
        return store;
    }, []);
};
