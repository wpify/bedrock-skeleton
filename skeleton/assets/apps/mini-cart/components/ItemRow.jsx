import {dispatch, useSelect, useDispatch} from "@wordpress/data";
import {_x, __} from '@wordpress/i18n';
const ItemRow = ({item}) => {
    return <>
        <a href={item.url} key={item.key} className="flex gap-2 items-center">
            <div dangerouslySetInnerHTML={{__html: item.image}} />
            <h3>{item.name}</h3>
        </a>
    </>
}
export default ItemRow;