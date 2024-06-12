import {dispatch, useSelect, useDispatch} from "@wordpress/data";
import {_x, __} from '@wordpress/i18n';
const ItemRow = ({item}) => {
    const {CART_STORE_KEY} = window.wc.wcBlocksData;
    const {removeItemFromCart, changeCartItemQuantity} = useDispatch(CART_STORE_KEY);
    return <>
        <div key={item.key} className="grid grid-cols-5 gap-2 items-center">
            {
                item.images.length > 0 && <img className="!max-w-10" src={item.images[0].thumbnail} alt={item.images[0].alt}/>
            }
            <h3>{item.name}</h3>

            <p>{item.totals.line_total}</p>
            <input type="number" value={item.quantity} onChange={(e) => changeCartItemQuantity(item.key, e.target.value)}/>
            <button onClick={() => {
                removeItemFromCart(item.key);
            }}>{__('Remove', 'wc-blocks')}</button>
        </div>
    </>
}
export default ItemRow;