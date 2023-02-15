<?php
if (!defined('_PS_VERSION_')) {
    exit;
}

class ProductLinks extends Module {
    
    public function __construct() {
        
        $this->name = 'productlinks';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Piotr Okrój';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.7.0.0',
            'max' => '8.99.99',
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Product Links');
        $this->description = $this->l('Description of my productlinks.');

        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');

    }

    public function install(){
        if(!$this->registerHook("displayFooterProduct")) return false;
        return parent::install();
    }


    public function hookDisplayFooterProduct($params){

        $id_lang = $this->context->language->id;
        $category = $params['category'];

        $products1 = Product::getProducts($id_lang, 1, 4, "id_product", "ASC", $category->id, true);
    

        $products = [];


        foreach($products1 as $product){
            $id_image = Product::getCover($product['id_product']);
            if($id_image){
                $image = new Image($id_image['id_image']);
                $image_url = _PS_BASE_URL_._THEME_PROD_DIR_.$image->getExistingImgPath().".jpg";
                $product['cover_url'] = $image_url;
                $product['id_product_attribute'] = Product::getDefaultAttribute($product['id_product']);
                $product['link'] = $this->context->link->getProductLink((int) $product['id_product']);
                $product['price_wt'] = Product::getPriceStatic($product['id_product']);
                $product['currency_sign'] = $this->context->currency->sign;
            }
            $products[] = $product;
        }



        $this->context->smarty->assign([
            'products' => $products
        ]);

        return $this->display(__FILE__, 'links.tpl');
    }


   


}
