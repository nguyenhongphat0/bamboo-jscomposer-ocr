<p class="title_block">{$title}</p>

{if isset($products) && $products}
    {include file="$tpl_dir./product-list.tpl" products=$products class='vc_products_supplier' id='vc_products_supplier'}
{/if}