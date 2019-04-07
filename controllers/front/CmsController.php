<?php
/*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Open Software License (OSL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http:* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http:*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http:*  International Registered Trademark & Property of PrestaShop SA
*/
class CmsController extends CmsControllerCore
{
	/*
    * module: jscomposer
    * date: 2015-12-13 23:41:45
    * version: 4.3.19
    */
    public function display()
	{
	  	    if((bool)Module::isEnabled('jscomposer'))
	  	    {
                   if(isset($this->cms->content)){
                        $this->cms->content = JsComposer::do_shortcode( $this->cms->content );

                        if(vc_mode() === 'page_editable'){                               
                             $this->cms->content = call_user_func(JsComposer::$front_editor_actions['vc_content'],$this->cms->content);
                                }
                   }
	  	    }
	  	    if((bool)Module::isEnabled('smartshortcode'))
	  	    {
                if(isset($this->cms->content)){
	  	           $smartshortcode = Module::getInstanceByName('smartshortcode');
	  	           $this->cms->content = $smartshortcode->parse( $this->cms->content );
                }
	  	    }
                    
                    return parent::display();
                    
	}
}
