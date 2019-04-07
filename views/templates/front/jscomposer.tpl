{if is_array($results)}
{foreach from=$results item="res"}
	{if $res.content != ''}{$res.content nofilter}{/if}
	{assign var=vc_optname value="{Configuration::get("_wpb_vccaw_{$res.id_vccontentanywhere}_{Context::getcontext()->language->id}_css")}"}
	<style type="text/css">
            {$vc_optname nofilter}
	</style>
{/foreach}
{else}
{$results nofilter}
{/if}
