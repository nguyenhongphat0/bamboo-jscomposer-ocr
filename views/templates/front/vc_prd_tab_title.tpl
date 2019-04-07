{if isset($vc_tab_title_results) && !empty($vc_tab_title_results)}
	{foreach from=$vc_tab_title_results item="vc_tab_title"}
		{if $vc_tab_title.title != ''}
			{*if $vc_product_tab_style != 'general'*}
				<li><a href="#vc-tab-{$vc_tab_title.id_vcproducttabcreator}" role="tab" data-toggle="tab">{$vc_tab_title.title}</a></li>
			{*/if*}
		{/if}
	{/foreach}
{/if}

