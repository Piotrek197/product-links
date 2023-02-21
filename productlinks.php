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
        Configuration::updateValue('PRODUCTLINKS_SAME_CATEGORY', false);
        Configuration::updateValue('PRODUCTLINKS_SAME_CART', 0);
        return parent::install();
    }

    public function uninstall(){
        if(!$this->unregisterHook("displayFooterProduct")) return false;
        Configuration::deleteByName('PRODUCTLINKS_SAME_CATEGORY');
        Configuration::deleteByName('PRODUCTLINKS_SAME_CART');
        return parent::uninstall();
    }


    public function hookDisplayFooterProduct($params){

        include _PS_MODULE_DIR_ .  $this->name . "/ProductHandler.php";
        $category = $params['category'];
        $product = $params['product'];

        $id_product = $product->getId();

        $ph = new ProductHandler();

        $category_products = [];
        $products = [];
        
        if(Configuration::get('PRODUCTLINKS_SAME_CATEGORY')){
            $category_products = $ph->getProductsFromCategory($category->id);
        }

        if(Configuration::get('PRODUCTLINKS_SAME_CART')){
            $products = $ph->getProducts($id_product);
        }
    
        $this->context->smarty->assign([
            'category_products' => $category_products, //$category_products,
            'buyed_products' => $products,
            "currency_sign" => $this->context->currency->sign
        ]);

        return $this->display(__FILE__, 'links.tpl');
    }

    public function getContent(){
        
        $output = '';

        if (Tools::isSubmit('submit' . $this->name)) {
            $sameCategory = Tools::getValue('PRODUCTLINKS_SAME_CATEGORY');
            $sameCart = Tools::getValue('PRODUCTLINKS_SAME_CART');

            Configuration::updateValue('PRODUCTLINKS_SAME_CATEGORY', $sameCategory);
            Configuration::updateValue('PRODUCTLINKS_SAME_CART', $sameCart);

            $output = $this->displayConfirmation($this->l('Settings updated'));
        }

        return $output . $this->renderForm();
    }

    private function renderForm(){
        $form = [
            'form' => [
                'legend' => [
                    'title' => $this->l('Settings'),
                ],
                'input' => [
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show products from the same category'),
                        'name' => 'PRODUCTLINKS_SAME_CATEGORY',
                        'is_bool' => true,
                        // 'desc' => "description",
                        'values' => [
                            [
                                'value' => 1,
                                'label' => $this->l("Yes")
                            ],
                            [
                                'value' => 0,
                                'label' => $this->l("No")
                            ]
                        ]
                        
                    ],
                    [
                        'type' => 'switch',
                        'label' => $this->l('Show products in cart'),
                        'name' => 'PRODUCTLINKS_SAME_CART',
                        'is_bool' => true,
                        // 'desc' => "description",
                        'values' => [
                            [
                                'value' => 1,
                                'label' => $this->l("Yes")
                            ],
                            [
                                'value' => 0,
                                'label' => $this->l("No")
                            ]
                        ]
                        
                    ],
                ],
                'submit' => [
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                ],
            ],
        ];
    
        $helper = new HelperForm();
    
        // Module, token and currentIndex
        $helper->table = $this->table;
        $helper->name_controller = $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = AdminController::$currentIndex . '&' . http_build_query(['configure' => $this->name]);
        $helper->submit_action = 'submit' . $this->name;
    
        // Default language
        $helper->default_form_language = (int) Configuration::get('PS_LANG_DEFAULT');
    
        // Load current value into the form
        $helper->fields_value['PRODUCTLINKS_SAME_CATEGORY'] = Tools::getValue('PRODUCTLINKS_SAME_CATEGORY', Configuration::get('PRODUCTLINKS_SAME_CATEGORY'));
        $helper->fields_value['PRODUCTLINKS_SAME_CART'] = Tools::getValue('PRODUCTLINKS_SAME_CART', Configuration::get('PRODUCTLINKS_SAME_CART'));
        return $helper->generateForm([$form]);
    }

}
