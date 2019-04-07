<div class="{$type}_slider">
    {$id = rand(000000,999999)}
    <p class="title_block">{$title}</p>
  <ul id="{$type}_slider_{$id}">
        {foreach from=$suppliers item=manufacturer name=manufacturers}
        <li>
            <a href="{$link->getSupplierLink($manufacturer.id_supplier)}"> 
                {$imgname=$manufacturer.id_supplier}
                {if !empty($man_img_size)}
                    {$imgname=$imgname|cat:'-'|cat:$man_img_size}
                {/if}
                {$imgname=$imgname|cat:'.jpg'}
                <img src="{$img_sup_dir}{$imgname}" alt="{$manufacturer.name}" title="{$manufacturer.name}"  />
            </a>
        </li>
          {/foreach}
   </ul>
</div>
<script type="text/javascript">
        
    //jQuery(function($) { 
        SdsJsOnLoadActions[SdsJsOnLoadActions.length] = function() {           
            var elem = $('#{$type}_slider_{$id}');
            var pelem = elem.parent();
            var maxSlides = {$maxslide};
            var wdi = ( pelem.width() / maxSlides );
            
            var sliderType = "{$slider_type}";
           
            if(typeof $.fn.bxSlider != 'undefined' && sliderType == 'bxslider') { 
                elem.bxSlider({                    
                    slideMargin : 0,
                    controls : true,
                    infiniteLoop : true,
                    responsive : true,
                    speed : parseInt({$speed}),
                    slideWidth : wdi,
                    minSlides : 1,
                    moveSlides : 2,
                    maxSlides : parseInt(maxSlides),
                    pager : false,                        
                    adaptiveHeight: true,
                    useCSS : false,
                    auto: true,
                    onSliderLoad: function () {
			            pelem.find('.bx-controls-direction').hide();
			            pelem.find('.bx-wrapper').hover(
			            function () { pelem.find('.bx-controls-direction').fadeIn(300); },
			            function () { pelem.find('.bx-controls-direction').fadeOut(300); }
			            );
		            }
                });
                //$('.manufacturer_slider .bx-wrapper').css('max-width','100% !important');
            }
        };            
    //});
</script>