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

class ManufacturerController extends ManufacturerControllerCore
{
	public function display()
	{
            
            if((bool)Module::isEnabled('jscomposer'))
            {
                   $this->manufacturer->description = JsComposer::do_shortcode( $this->manufacturer->description );

            }
            if((bool)Module::isEnabled('smartshortcode'))
            {
                   $smartshortcode = Module::getInstanceByName('smartshortcode');
                   $this->manufacturer->description = $smartshortcode->parse( $this->manufacturer->description );
            }
                    
            return parent::display();
                    
	}
}
