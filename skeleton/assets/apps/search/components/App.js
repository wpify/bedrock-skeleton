import React, {useCallback, useState, useRef, useEffect} from 'react';
import {dispatch, useSelect, useDispatch} from "@wordpress/data";
import {_x, __} from '@wordpress/i18n';
import ItemRow from "@/mini-cart/components/ItemRow";
import ky from 'ky';
import {useCartItems} from "@/hooks.js";

function App() {
    const {CART_STORE_KEY} = window.wc.wcBlocksData;
    const [query, setQuery] = useState(null)
    const [results, setResults] = useState(null)

    const handleSearch = () => {
        ky.post(`${window.search.api_url}/search`, {json: {query}}).json().then(data => {
            console.log(data);
            setResults(data);
        });
    }

    useEffect(() => {
        if (!query) return;
        const timeOutId = setTimeout(() => handleSearch(), 500);
        return () => clearTimeout(timeOutId);
    }, [query]);


    return <>
        <input type="text" className="border-2" onChange={e => setQuery(e.target.value)}/>
        <div>
            {results && results.products?.map((item, index) => {
                return <ItemRow key={index} item={item}/>
            })}
        </div>
    </>
}

export default App;