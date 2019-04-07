{extends file="helpers/form/form.tpl"}
{block name="label"}
    {if $input.type == 'vc_content_mod_type'}
            <script type="text/javascript">
                $(document).ready(function(){
                    content_type_config(0);
                    $("input:radio[name=content_type]").on('click',function(){
                        content_type_config(500);
                    });
                    // start method
                    function content_type_config(vcspeed){
                        var content_val = $('input:radio[name=content_type]:checked').val();
                        if(content_val == 1){
                            content_type_checked(vcspeed);
                        }else{
                            $(".composer-switch").hide(vcspeed);
                            content_type_unchecked(vcspeed);
                        }
                    }
                    function content_type_checked(vcspeed){
                        $(".composer-switch").show(vcspeed);
                        $("#modules_list").parent().parent().hide(vcspeed);
                        $("#module_hook_list").parent().parent().hide(vcspeed);
                        // $("#content_"+id_language+".vc_content_class").parent().parent().show(vcspeed);
                    }
                    function content_type_unchecked(vcspeed){
                        $("#wpb_visual_composer").hide(vcspeed);
                        $(".composer-switch").hide(vcspeed);
                        $("#modules_list").parent().parent().show(vcspeed);
                        $("#modules_list").parent().parent().show(vcspeed);
                        $("#module_hook_list").parent().parent().show(vcspeed);
                        // $("#content_"+id_language+".vc_content_class").parent().parent().hide(vcspeed);
                    }
                    // End method
                    // Start Ajax EXEC
                        var module_name = $('#modules_list option:selected').val();
                        var hook_name = $('#module_hook_list option:selected').val();
                        if(module_name !== undefined){
                            {if !isset($input.vc_is_edit) && empty($input.vc_is_edit)}
                                hookfilter_ajax(module_name,'');
                            {/if}
                        }
                        $('#modules_list').change(function(){
                            var module_name = $('#modules_list option:selected').val();
                            hookfilter_ajax(module_name,'');
                        });
                    // END Ajax EXEC
                });
                function hookfilter_ajax(vc_module_name,vc_hook_name){
                    $.ajax({
                        type: "POST",
                        url: "{$input.vc_ajax_url}",
                        data: "vc_module_name="+vc_module_name+"&vc_hook_name="+vc_hook_name+"hook_filter=1",
                        success: function(data){
                            $('#module_hook_list').html(data);
                        }
                    });
                }
            </script>
    {/if}
    {if $input.type == 'vc_content_type'}
        <input type="hidden" value="{$input.prd_specify_values}" name="prd_specify" id="prd_specify_id_text">

        <script type="text/javascript">
            $(document).ready(function(){
                prd_page_values_config(0);
                $("input:radio[name=prd_page]").on('click',function(){
                    prd_page_values_config(500);
                });
                $('form[id="vcproducttabcreator_form"] input[id="prd_specify_id_text"]').each(function(){
                    $(this).change(prd_textchange).change();
                });
                $('form[id="vcproducttabcreator_form"] select[id="prd_specify_id"]').each(function(){
                    $(this).change(prd_listchange);
                });
            });
            function prd_page_values_checked(vcspeed){
                $("#ajax_choose_product").closest('.form-group').hide(vcspeed);
            }
            function prd_page_values_config(vcspeed){
                var prd_page_val = $('input:radio[name=prd_page]:checked').val();
                if(prd_page_val == 1){
                    prd_page_values_checked(vcspeed);
                }else{
                    prd_page_values_unchecked(vcspeed);
                }
            }
            function prd_page_values_unchecked(vcspeed){
                $("#ajax_choose_product").closest('.form-group').show(vcspeed);
            }
            // START Change Prd selection
            function prd_listchange() {
                var obj = $(this);
                var str = obj.val().join(',');
                obj.closest('form').find('#prd_specify_id_text').val(str);
            }
            function prd_textchange() {
                var obj = $(this);
                var list = obj.closest('form').find('#prd_specify_id');
                var values = obj.val().split(',');
                var len = values.length;
                list.find('option').prop('selected', false);
                for (var i = 0; i < len; i++)
                    list.find('option[value="' + $.trim(values[i]) + '"]').prop('selected', true);
            }
            // END Change Prd selection
        </script>
        <style>
        .bootstrap select[multiple], .bootstrap select[size] {
            height: 250px;
        }
        .bootstrap .prd_specify_class.fixed-width-xl,.bootstrap .cat_specify_class.fixed-width-xl,.bootstrap .cms_specify_class.fixed-width-xl {
            width: 450px !important;
        }
        </style>
    {elseif $input.type == 'ajaxproducts'}        
        {assign var=accessories value=$input.saved}
        <label class="control-label col-lg-3 " for="prd_page"> {$input.label} </label>
        <div class="col-lg-5">
            <input type="hidden" name="inputAccessories" id="inputAccessories" value="{if !empty($accessories)}{foreach from=$accessories item=accessory}{$accessory.id_product}-{/foreach}{/if}" />
            <input type="hidden" name="nameAccessories" id="nameAccessories" value="{if !empty($accessories)}{foreach from=$accessories item=accessory}{$accessory.name|escape:'html':'UTF-8'}¤{/foreach}{/if}" />
            <div id="ajax_choose_product">
                <div class="input-group">
                    <input type="text" id="product_autocomplete_input" name="product_autocomplete_input"/>
                    <span class="input-group-addon"><i class="icon-search"></i></span>
                </div>
            </div>
            <div id="divAccessories">
                {if !empty($accessories)}
                {foreach from=$accessories item=accessory} 
                    <div class="form-control-static">
                            <button type="button" class="btn btn-default delAccessory" name="{$accessory.id_product}">
                                <i class="icon-remove text-danger"></i>
                            </button>
                        {$accessory.name|escape:'html':'UTF-8'}{if isset($accessory.reference)}&nbsp;{l s='(ref: %s)' sprintf=$accessory.reference}{/if}
                    </div>
                {/foreach}
                {/if}
            </div>
        </div>

    <script type="text/javascript">
        $(document).ready(function() {

            var id_product = $('input[name=id_product]').first().val();

            $('#product_autocomplete_input')
                    .autocomplete(window.vc_ajaxurl+'&action=vcca_ajax_get_products', {
                minChars: 1,
                autoFill: true,
                max: 20,
                matchContains: true,
                mustMatch: false,
                scroll: false,
                cacheLength: 0,
                formatItem: function(item) {                    
                    return item[1] + ' - ' + item[0];
                }
            }).result(addAccessory);
            $('#product_autocomplete_input').setOptions({
            
                extraParams: {
                    excludeIds: getAccessoriesIds()
                }
            });
            function delAccessory(id)
            {
                        var div = $('#divAccessories');
                        var input = $('#inputAccessories');
                        var name = $('#nameAccessories');

                        // Cut hidden fields in array
                        var inputCut = input.val().split('-');
                        var nameCut = name.val().split('¤');

                        if (inputCut.length != nameCut.length)
                            return jAlert('Bad size');
                        
                        // Reset all hidden fields
                        input.val('');
                        name.val('');
                        div.html('');
                        var inputVal = '', nameVal = '', divHtml = '';
                        for (var i in inputCut)
                    {
                                    // If empty, error, next
                            if (!inputCut[i] || !nameCut[i])
                                continue;

                            if(typeof inputCut[i] == 'function') // to resolve jPaq issues
                                continue;
                            
                            // Add to hidden fields no selected products OR add to select field selected product
                            if (inputCut[i] != id)
                            {
                                inputVal += inputCut[i] + '-';
                                nameVal += nameCut[i] + '¤';
                                divHtml += '<div class="form-control-static"><button type="button" class="delAccessory btn btn-default" name="' + inputCut[i] + '"><i class="icon-remove text-danger"></i></button>&nbsp;' + nameCut[i] + '</div>';
                            }
                            else
                                $('#selectAccessories').append('<option selected="selected" value="' + inputCut[i] + '-' + nameCut[i] + '">' + inputCut[i] + ' - ' + nameCut[i] + '</option>');
                    }
                    
                    input.val(inputVal);
                    name.val(nameVal);
                    div.html(divHtml);
                    $('#product_autocomplete_input').setOptions({
                        extraParams: {
                            excludeIds: getAccessoriesIds()
                        }
                    });
                }


                $('#divAccessories').on('click', '.delAccessory',  function() {
                    delAccessory($(this).attr('name'));
                });

                                    
                                    function getAccessoriesIds() {

                                        /*if ($('#inputAccessories').val() === undefined){
                                            return id_product;
                                        }*/
                                        return $('#inputAccessories').val().replace(/\-/g, ',');
                                    }
                                    function addAccessory(event, data, formatted)
            {
                
            
                        if (data == null)
                            return false;
                        var productId = data[1];
                        var productName = data[0];

                        var $divAccessories = $('#divAccessories');
                        var $inputAccessories = $('#inputAccessories');
                        var $nameAccessories = $('#nameAccessories');

                        /* delete product from select + add product line to the div, input_name, input_ids elements */
                        $divAccessories.html($divAccessories.html() + '<div class="form-control-static"><button type="button" class="delAccessory btn btn-default" name="' + productId + '"><i class="icon-remove text-danger"></i></button>&nbsp;' + productName + '</div>');
                        $nameAccessories.val($nameAccessories.val() + productName + '¤');
                        $inputAccessories.val($inputAccessories.val() + productId + '-');
                        $('#product_autocomplete_input').val('');
                        $('#product_autocomplete_input').setOptions({
                            extraParams: {
                                excludeIds: getAccessoriesIds()
                            }
                        });
                    }
                    ;


                });


    </script>
    {else}
        {$smarty.block.parent}
    {/if}
{/block}
