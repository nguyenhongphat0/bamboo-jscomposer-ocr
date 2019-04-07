{if isset($vc_tab_content_results) && !empty($vc_tab_content_results)}
	{foreach from=$vc_tab_content_results item="vc_tab_content"}
		{if $vc_tab_content.content != ''}
			{if $vc_product_tab_style != 'general'}
				<div class="tab-pane" id="vc-tab-{$vc_tab_content.id_vcproducttabcreator}">
					<section class="page-product-box">
						<div  class="rte">
                                                    {$vc_tab_content.content|unescape:"html"}
						</div>
					</section>
				</div>
			{else}
				<section class="page-product-box">
					<h3 class="page-product-heading">{$vc_tab_content.title}</h3>
					<div  class="rte">
                                            {$vc_tab_content.content|unescape:"html"}
					</div>
				</section>
			{/if}
			{assign var=vct_optname value="{Configuration::get("_wpb_vctc_{$vc_tab_content.id_vcproducttabcreator}_{Context::getcontext()->language->id}_css")}"}
			<style type="text/css">
				{$vct_optname}
			</style>
		{/if}
	{/foreach}
{/if}