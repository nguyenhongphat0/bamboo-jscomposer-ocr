{foreach from=$products item=product name=products}
<div class="vc_add_to_cart" style="{$style}">
    <article class="product-miniature js-product-miniature" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product}" itemscope="" itemtype="http://schema.org/Product">
        <div class="product-description">
          <h1 class="h3 product-title" itemprop="name"><a>{$product.name}</a></h1>
          <div class="product-price-and-shipping">
            <span itemprop="price" class="price">{if (((isset($product.show_price) && $product.show_price) || (isset($product.available_for_order) && $product.available_for_order)))}{if isset($product.specific_prices) && $product.specific_prices && isset($product.specific_prices.reduction) && $product.specific_prices.reduction > 0}<span class="regular-price">{Tools::displayPrice($product.price_without_reduction)}</span>{/if}<span itemprop="price" class="price">{Tools::displayPrice($product.price)}</span>{/if}</span>
          </div>
          <div class="product-price-and-shipping">
              <a class="quick-view" href="#" data-link-action="quickview">
                <i class="material-icons search"></i> Quick view
              </a>
          </div>
        </div>
        <ul class="product-flags"></ul>
    </article>
</div>
{/foreach}
