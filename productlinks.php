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

        include _PS_MODULE_DIR_ .  $this->name . "/ProductHandler.php";
        $category = $params['category'];
        $product = $params['product'];

        $id_product = $product->getId();

        $ph = new ProductHandler();

        $category_products = $ph->getProductsFromCategory($category->id);
        $products = $ph->getProducts($id_product);


        $this->context->smarty->assign([
            'category_products' => $category_products, //$category_products,
            'buyed_products' => $products,
            "currency_sign" => $this->context->currency->sign
        ]);

        return $this->display(__FILE__, 'links.tpl');
    }

}
