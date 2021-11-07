/**
 * Class Local Cart
 * 
 * Using Cookie for Local Storage
 * 
 * ClientCode ::
 * lokaCCart.addProduct( "7sa87sf6a8faf7989fa", 51, 1 );
 * lokaCCart.removeProduct( "7sa87sf6a8faf7989fa", 51 );
 * lokaCCart.addQuantity( "7sa87sf6a8faf7989fa", 51, 1 );
 * lokaCCart.reduceQuantity( "7sa87sf6a8faf7989fa", 51, 1 );
 * lokaCCart.get();
 */

const lokaCCart = {

    create: () => {
        lokaCCookie.set("lokacommerce_cart", "[]", 28); // 28 Hari Expired
    },

    skelton: (cart_id, item) => {

        let cart = JSON.parse(lokaCCookie.get("lokacommerce_cart"));
        let items = cart.items;

        // if produk kosong
        items.push(item);
        // produk ada -> manipulasi quantity

        let object = {
            "hash": cart_id,
            "token": "XSRF Token",
            "items": items
        };

        return object;
    },

    // User dapat menambah barang ke keranjang
    addProduct: (cart_id, product_id, product_qty, token = "") => {

        // Sudah buat keranjang di browser ?
        if (lokaCCookie.get("lokacommerce_cart") == "") {
            lokaCCart.create();
        }

        // Produk tidak tersedia di keranjang 
        // Add to Cart
        let item = [{
            "id": product_id,
            "qty": product_qty
        }];

        let object = lokaCCart.skelton(cart_id, item);
        lokaCCookie.set("lokacommerce_cart", JSON.stringify(object), 28);
    },

    // User dapat menghapus barang dari keranjang
    removeProduct: (cart_id, product_id) => {},

    // User dapat menambah kuantiti barang
    addQuantity: (cart_id, product_id, qty_increase) => {

        let cart = JSON.parse(lokaCCookie.get("lokacommerce_cart"));
        let items = cart.items;

        // Check Product ID exist
        let item = [{
            "id": product_id,
            "qty": items[0].qty + parseInt(qty_increase)
        }];

        let object = lokaCCart.skelton(cart_id, item);
        lokaCCookie.set("lokacommerce_cart", JSON.stringify(object), 28);
    },

    // User dapat mengurangi kuantiti barang
    reduceQuantity: (cart_id, product_id, qty_decrease) => {
        let cart = JSON.parse(lokaCCookie.get("lokacommerce_cart"));
        let items = cart.items;

        // Check Product ID exist
        let item = [{
            "id": product_id,
            "qty": items[0].qty - parseInt(qty_decrease)
        }];

        let object = lokaCCart.skelton(cart_id, item);
        lokaCCookie.set("lokacommerce_cart", JSON.stringify(object), 28);
    },

    // Sistem dapat mengambil data keranjang
    get: () => {
        let cart = JSON.parse(lokaCCookie.get("lokacommerce_cart"));

        // Total Product in Cart
        cart.item_count = cart.items.length;

        // Total Quantity in Cart

        return cart;
    }
}