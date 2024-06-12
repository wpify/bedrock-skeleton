import React, { useState} from 'react';
import ItemRow from "@/mini-cart/components/ItemRow";
import {useCartItems} from "@/hooks.js";

function App() {
    const [isShown, setIsShown] = useState(false)
    const items = useCartItems();

    return <>
        <div onClick={() => setIsShown(!isShown)}>show cart</div>
        { isShown && <>
            {items.map((item) => {
                return <ItemRow item={item} key={item.key}/>
            })
            }
        </> }
    </>
}

export default App;