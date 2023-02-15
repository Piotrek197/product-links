{if $products}
  <h2 style="margin-bottom: 2rem;">Inne produkty z tej kategorii</h2>

  <style>
      .wishlist-button-add{
          /* position: absolute;
          top: 0.635rem;
          right: 0.635rem;
          z-index: 10; */
          display:none;
      }


  </style>
  {* {$products|dump} *}

  {foreach $products item=$product}
      {* <p>{$product['name']}</p> *}
      <div class="product col-xs-12 col-sm-6 col-lg-4 col-xl-3">
    <article class="product-miniature js-product-miniature" data-id-product="{$product['id_product']}" data-id-product-attribute="{$product['id_product_attribute']}">
      <div class="thumbnail-container">
        <div class="thumbnail-top">
          
          <a href="{$product['link']}" class="thumbnail product-thumbnail">
            <img src="{$product['cover_url']}" alt="{$product['name']}" loading="lazy" data-full-size-image-url="https://prestashop.piotr-okroj.hmcloud.pl/img/p/1/1-large_default.jpg" width="250" height="250">
          </a>
                    

          <div class="highlighted-informations">
            
              <a class="quick-view js-quick-view" href="#" data-link-action="quickview">
                <i class="material-icons search"></i> Szybki podgląd
              </a>
              
              <div class="variant-links">
              </div>
                        
          </div>
        </div>

        <div class="product-description">
          <h3 class="h3 product-title">
            <a href="{$product['link']}" content="https://prestashop.piotr-okroj.hmcloud.pl/index.php?id_product=1&amp;id_product_attribute=1&amp;rewrite=hummingbird-printed-t-shirt&amp;controller=product&amp;id_lang=2#/1-rozmiar-s/8-kolor-bialy">
              {$product['name']}
            </a>
          </h3>
                    

          
          <div class="product-price-and-shipping">
            
            {* <span class="regular-price" aria-label="Cena podstawowa">29,40&nbsp;zł</span> *}
            {* <span class="discount-percentage discount-product">-20%</span> *}
            
            <span class="price" aria-label="Cena">{$product['price_wt']|string_format:"%.2f"} {$product['currency_sign']}</span>

          </div>

          
        </div>
      </div>
    </article>
  </div>
  {/foreach}
{/if}
