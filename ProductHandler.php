<?php

class ProductHandler {

    private $context;

    public function __construct(){

        $this->context = Context::getContext();
    }

    public function getProductsFromCategory($id_category){
        
        $id_lang = $this->context->language->id;
        $products1 = Product::getProducts($id_lang, 1, 4, "id_product", "ASC", (int)$id_category, true);

        $products = [];

        foreach($products1 as $product){
            $id_image = Product::getCover($product['id_product']);
            if($id_image){
                $image = new Image($id_image['id_image']);
                $image_url = _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath().".jpg";
                $product['cover_url'] = $image_url;
                $product['id_product_attribute'] = Product::getDefaultAttribute($product['id_product']);
                $product['link'] = $this->context->link->getProductLink((int) $product['id_product']);
                $product['price_t'] = Product::getPriceStatic($product['id_product']);
            }
            $products[] = $product;
        }

        return $products;
    }


    public function getProducts($id_product){

        $sql = new DbQuery();
        $sql->select('id_cart, JSON_ARRAYAGG(id_product) as products');
        $sql->from('cart_product');
        $sql->groupBy('id_cart');
        $cart_products = Db::getInstance()->executeS($sql);

        $products_contest = [];

        foreach($cart_products as $cp){

            $products = json_decode($cp['products']);

            // if(in_array($id_product, $products)){
                foreach($products as $p){
                    if(isset($products_contest[$p])){
                        $products_contest[$p]++;
                    }else{
                        $products_contest[$p] = 1;
                    }
                }
            // }
        }
        asort($products_contest);

        $limit = 2;

        $temp = array_keys($products_contest);
        $products = [];

        for($i = 0; $i < $limit; $i++){
            $index = array_pop($temp);
            $products[] = $this->getProductDetails($index);
        }

        return $products;

    }

    private function getProductDetails($id_product){

        $data_product = new Product($id_product);
        $product = [];

        $product['id_product'] = $id_product;
        $product['name'] = $data_product->name[$this->context->language->id];
        $product['id_product_attribute'] = Product::getDefaultAttribute($id_product);
        $product['link'] = $this->context->link->getProductLink((int) $id_product);
        $product['price_t'] = Product::getPriceStatic($id_product);

        
        $id_image = Product::getCover($id_product);

        if($id_image){
            $image = new Image($id_image['id_image']);
            $image_url = _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath().".jpg";
            $product['cover_url'] = $image_url;
        }

        return $product;
        
    }
}