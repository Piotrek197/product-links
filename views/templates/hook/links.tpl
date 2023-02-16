<style>
.wishlist-button-add{
    display:none;
}
</style>

{if $category_products}
  <div class="productlinks-container">
    <h2 style="margin-bottom: 2rem;">Inne produkty z tej kategorii</h2>

    <div style="display:flex;">
      {foreach $category_products item=$product}
        {include file="../_partials/miniature.tpl" product=$product}
      {/foreach}
    </div>
  </div>

{/if}

{if $buyed_products}
  <div class="productlinks-container">
    <h2 style="margin-bottom: 2rem;">Klienci kupowali ten produkt z</h2>
    <div style="display:flex;">
      {foreach $buyed_products item=$product}
        {include file="../_partials/miniature.tpl" product=$product}
      {/foreach}
    </div>
  </div>


{/if}
