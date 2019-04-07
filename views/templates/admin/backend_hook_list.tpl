{*
* 2007-2016 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

 
<div id="addNewModule" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form method="post" action="" id='addNewModuleForm'>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add new module</h4>
      </div>
      <div class="modal-body">
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Module name</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="js_module_name"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Controller name</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="js_controller_name"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">DB Table Name</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="js_controller_table"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Identifier</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="js_controller_identifier"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Content Field</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="js_controller_field"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Short Name</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="js_controller_shortname"></div>
      	</div>
      </div>
      <div class="modal-footer">
        <input type="submit" class="btn btn-default value="submit" name="customhookadd" >
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
</form>
  </div>
</div>

<div id="editModule" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form method="post" action="" id='editModule'>
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Details</h4>
      </div>

      <div class="modal-body">
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Module name</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="edit_module_name" disabled="disabled"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Controller name</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="edit_controller_name" disabled="disabled"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">DB Table Name</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="edit_dbtable" disabled="disabled"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Identifier</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="edit_identifier" disabled="disabled"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Content Field</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="edit_field" disabled="disabled"></div>
      	</div>
      	<br>
      	<div class='row'>
      		<div class="col-lg-5 col-offset-1 text-right">Short Name</div>
      		<div class="col-lg-5 col-offset-1"><input type="text" id="edit_short_name" disabled="disabled"></div>
      	</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
</form>
  </div>
</div>

<div class="panel"><h3><i class="icon-list-ul"></i> {l s='Module list' mod='jscomposer'}
	<span class="panel-heading-action">
		<a id="desc-product-new" class="list-toolbar-btn" href="javascript:void(0);">
			<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Add new' mod='jscomposer'}" data-html="true">
				<i class="process-icon-new " data-toggle="modal" data-target="#addNewModule"></i>
			</span>
		</a>
	</span>
	</h3>
	<div id="slidesContent">
		<div id="js_active_modules">
				<div class="panel">
					<div class="row">
						<div class="col-lg-2"><h4>Name</h4></div>
						<div class="col-lg-2 text-center"><h4>Frontend</h4></div>
						<div class="col-lg-2 text-center"><h4>Editor Status</h4></div>
						<div class="col-lg-2 text-center"><h4>Backend Editor</h4></div>
						<div class="col-lg-2 text-center"><h4>Frontend Editor</h4></div>
						<div class="col-lg-2 text-center"><h4>Action</h4></div>
					</div>
				</div>

			{foreach from=$controllers key=k item=controller}
				<div id="module_list_{$controller.identifier}" class="panel">
					<div class="row">
						<div class="col-lg-2">
							<h4 class="pull-left">
								#{$controller.identifier} - {$controller.controller}
							</h4>
						</div>

						{if empty($controller.module_frontend_enable)} {$controller.module_frontend_enable = 0} {/if}
						{$controller.module_frontend_enable_html = '<i class="icon-ban"></i>'}
						{if $controller.module_frontend_enable == 1}
							{$controller.module_frontend_enable_html = '<i class="icon-check"></i>'}
						{/if}

						<div class="col-lg-2 text-center">
						{IF $controller.type == 'core' AND  $controller.controller != 'Adminvccontentanywhere'}
							<a href="javascript:void(0);" class='change_module_frontend_enable' data-identifier="{$controller.identifier}" data-js_module_name='{$k}' data-change_module_frontend_enable='{$controller.module_frontend_enable}'>{$controller.module_frontend_enable_html}</a>
						{/IF}
						</div>

						{if empty($controller.module_status)} {$controller.module_status = 0} {/if}

						{$controller.module_editor_html = '<i class="icon-ban"></i>'}
						{if $controller.module_status == 1}
							{$controller.module_editor_html = '<i class="icon-check"></i>'}
						{/if}

						<div class="col-lg-2 text-center">
							<a href="javascript:void(0);" class='change_editor_status' data-identifier="{$controller.identifier}" data-js_module_name='{$k}' data-js_module_editor_status='{$controller.module_status}'>{$controller.module_editor_html}</a>
						</div>

						{if empty($controller.module_frontend_status)} {$controller.module_frontend_status = 0} {/if}
						{if empty($controller.module_backend_status)} {$controller.module_backend_status = 0} {/if}

						{$controller.module_frontend_html = '<i class="icon-ban"></i>'}
						{$controller.module_backend_html = '<i class="icon-ban"></i>'}
						{if $controller.module_frontend_status == 1}
							{$controller.module_frontend_html = '<i class="icon-check"></i>'}
						{/if}
						{if $controller.module_backend_status == 1}
							{$controller.module_backend_html = '<i class="icon-check"></i>'}
						{/if}

						<div class="col-lg-2 text-center">
							<a href="javascript:void(0);" class='js_module_status' data-js_module_status_for='module_backend_status' data-js_module_status='{$controller.module_backend_status}' data-js_module_name='{$k}'>{$controller.module_backend_html}</a>
						</div>

						<div class="col-lg-2 text-center">
							<a href="javascript:void(0);" class='js_module_status' data-js_module_status_for='module_frontend_status' data-js_module_status='{$controller.module_frontend_status}' data-js_module_name='{$k}'>{$controller.module_frontend_html}</a>
						</div>

						<div class="col-lg-2 text-center">
							<div class="btn-group-action pull-right">

							{if empty($controller.dbtable)} {$controller.dbtable = ''} {/if}

								<a data-edit_module_name="{$k}" data-edit_controller_name="{$controller.controller}" data-edit_dbtable="{$controller.dbtable}" data-edit_identifier="{$controller.identifier}" data-edit_field="{$controller.field}" data-edit_short_name="{$controller.shortname}" class="edit_view_details btn btn-default"
									href="javascript:void(0);">
									<i class="icon-search"></i>&nbsp;
									{l s='Details' mod='jscomposer'}
								</a>

								{if empty($controller.type)} {$controller.type = 'custom'} {/if}

								{IF $controller.type == 'core'}
								<a class="btn btn-danger disabled" href="javascript:void(0)">
									<i class="icon-ban"></i>&nbsp;
									{l s='Delete' mod='jscomposer'}
								</a>
								{ELSE}
								<a class="btn btn-danger js_module_remove" href="javascript:void(0)" data-js_module_name='{$k}' data-identifier="{$controller.identifier}">
									<i class="icon-trash"></i>&nbsp;
									{l s='Delete' mod='jscomposer'}
								</a>
								{/IF}
							</div>
						</div>
					</div>
				</div>
			{/foreach}
		</div>
	</div>
</div>

<script type="text/javascript">
$(document.body).on('click', '.edit_view_details' ,function(){
	var edit_module_name = $(this).attr("data-edit_module_name");
	var edit_controller_name = $(this).attr("data-edit_controller_name");
	var edit_dbtable = $(this).attr("data-edit_dbtable");
	var edit_identifier = $(this).attr("data-edit_identifier");
	var edit_field = $(this).attr("data-edit_field");
	var edit_short_name = $(this).attr("data-edit_short_name");

	$('#edit_module_name').val(edit_module_name);
	$('#edit_controller_name').val(edit_controller_name);
	$('#edit_dbtable').val(edit_dbtable);
	$('#edit_identifier').val(edit_identifier);
	$('#edit_field').val(edit_field);
	$('#edit_short_name').val(edit_short_name);

	$('#editModule').modal('toggle');

});

$(document.body).on('click', '.change_module_frontend_enable' ,function(){
	var js_module_frontend_enable = $(this).attr("data-change_module_frontend_enable");

	js_module_frontend_enable = (js_module_frontend_enable == 0) ? 1 : 0;
	var js_module_html = (js_module_frontend_enable == 0) ? '<i class="icon-ban"></i>' : '<i class="icon-check"></i>';
	var url_module_name = $(this).attr("data-js_module_name");
	var status = js_module_frontend_enable;

	var url_module_name = $(this).attr("data-js_module_name");
	
	var identifier = $(this);
	$.ajax({
	  type: 'POST',
	  url: '{$url_admin_ajax}',
	  data: 'method=changeFrontendEnableStatus&module_name='+url_module_name+'&status='+status,
	  success: function(json) {
	  	identifier.attr('data-change_module_frontend_enable', status);
		identifier.html(js_module_html);
	  }
	});
});
$(document.body).on('click', '.change_editor_status' ,function(){
	var js_module_editor_status = $(this).attr("data-js_module_editor_status");

	js_module_editor_status = (js_module_editor_status == 0) ? 1 : 0;
	var js_module_html = (js_module_editor_status == 0) ? '<i class="icon-ban"></i>' : '<i class="icon-check"></i>';
	var url_module_name = $(this).attr("data-js_module_name");
	var status = js_module_editor_status;

	var url_module_name = $(this).attr("data-js_module_name");
	
	var identifier = $(this);
	$.ajax({
	  type: 'POST',
	  url: '{$url_admin_ajax}',
	  data: 'method=changeJsModuleStatus&module_name='+url_module_name+'&status='+status,
	  success: function(json) {
	  	identifier.attr('data-js_module_editor_status', status);
		identifier.html(js_module_html);
	  }
	});
});
$(document.body).on('click', '.js_module_remove' ,function(){
	var identifier = $(this).attr("data-identifier");
	var url_module_name = $(this).attr("data-js_module_name");
	$.ajax({
	  type: 'POST',
	  url: '{$url_admin_ajax}',
	  data: 'method=deleteJsModule&module_name='+url_module_name,
	  success: function(json) {
		$('#module_list_'+identifier).hide('slow');
	  }
	});
});
$(document.body).on('click', '.js_module_status' ,function(){
	var js_module_status = $(this).attr("data-js_module_status");
	var js_module_status_for = $(this).attr("data-js_module_status_for");

	js_module_status = (js_module_status == 0) ? 1 : 0;
	var js_module_html = (js_module_status == 0) ? '<i class="icon-ban"></i>' : '<i class="icon-check"></i>';
	var url_action_name = 'change_status';
	var url_post_type = 'ajax';
	var url_module_name = $(this).attr("data-js_module_name");
	var status = js_module_status;


	var identifier = $(this);
	$.ajax({
	  type: 'POST',
	  url: '{$url_admin_ajax}',
	  data: 'method=changeJsModuleBFStatus&module_name='+url_module_name+'&js_module_status_for='+js_module_status_for+'&status='+status,
	  success: function(json) {
	  	json = JSON.parse(json);
	  	if(json.status){
			identifier.attr('data-js_module_status', js_module_status);
			identifier.html(js_module_html);
		} else {
			$('#ajaxBox').html('<div class="bootstrap"><div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">×</button><ul class="list-unstyled"><li>You need to activate JsComposer</li></ul></div></div>');
			$("html, body").animate({ scrollTop: 0 }, "slow");
			$('#ajaxBox').show();
		}
	  }
	});
});

$(document).ready(function () {
	$("#addNewModuleForm").on('submit', function(e) {
		var js_module_name = $('#js_module_name').val();
		var js_controller_name = $('#js_controller_name').val();
		var js_controller_identifier = $('#js_controller_identifier').val();
		var js_controller_field = $('#js_controller_field').val();
		var js_controller_shortname = $('#js_controller_shortname').val();
		var js_controller_table = $('#js_controller_table').val();
		
		$.ajax({
		  type: 'POST',
		  url: '{$url_admin_ajax}',
		  data: 'method=addJsModule&js_module_name='+js_module_name+'&js_controller_name='+js_controller_name+'&js_controller_identifier='+js_controller_identifier+'&js_controller_field='+js_controller_field+'&js_controller_shortname='+js_controller_shortname+'&js_controller_table='+js_controller_table,
		  success: function(json) {
		  	json = JSON.parse(json);
		  	if(json.status){
			  	if(json.success){
				  	tmp_html = '';
					tmp_html += '<div id="module_list_'+js_controller_identifier+'" class="panel">';
						tmp_html += '<div class="row">';
							tmp_html += '<div class="col-lg-2">';
								tmp_html += '<h4 class="pull-left">';
								tmp_html += '#'+js_controller_identifier+' - '+js_controller_name;
								tmp_html += '</h4>';
							tmp_html += '</div>';

							tmp_html += '<div class="col-lg-2 text-center">';
							tmp_html += '</div>';
							tmp_html += '<div class="col-lg-2 text-center">';

							tmp_html += '<a href="javascript:void(0);" class="change_editor_status" data-identifier="'+js_controller_identifier+'" data-js_module_name="'+js_module_name+'" data-js_module_editor_status=1><i class="icon-check"></i></a>';

							tmp_html += '</div>';

							tmp_html += '<div class="col-lg-2 text-center">';
								tmp_html += '<a href="javascript:void(0);" class="js_module_status"  data-js_module_status_for=\'module_backend_status\' data-js_module_status="1" data-js_module_name="'+js_module_name+'"><i class="icon-check"></i></a>';
							tmp_html += '</div>';
							tmp_html += '<div class="col-lg-2 text-center">';
								tmp_html += '<a href="javascript:void(0);" class="js_module_status" data-js_module_status_for=\'module_frontend_status\' data-js_module_status="1" data-js_module_name="'+js_module_name+'"><i class="icon-check"></i></a>';
							tmp_html += '</div>';
							tmp_html += '<div class="col-lg-2 text-center">';
								tmp_html += '<div class="btn-group-action pull-right">';
									tmp_html += '<a class="btn btn-default js_module_remove" href="javascript:void(0)" data-js_module_name="'+js_module_name+'" data-identifier="'+js_controller_identifier+'"><i class="icon-trash"></i>&nbsp;Delete</a>';
								tmp_html += '</div>';
							tmp_html += '</div>';
						tmp_html += '</div>';
					tmp_html += '</div>';
					$('#js_active_modules').html($('#js_active_modules').html()+tmp_html);
					$('#addNewModule').modal('toggle');
				} else {
					alert('Controller \''+js_controller_name+'\' under module \''+js_module_name+'\' already exists');
				}
			} else {
				$('#addNewModule').modal('toggle');
				$('#ajaxBox').html('<div class="bootstrap"><div class="alert alert-warning"><button type="button" class="close" data-dismiss="alert">×</button><ul class="list-unstyled"><li>You need to activate JsComposer</li></ul></div></div>');
				$("html, body").animate({ scrollTop: 0 }, "slow");
				$('#ajaxBox').show('slow');
			}
		  }
		});
		return false;
	});

});

</script>
