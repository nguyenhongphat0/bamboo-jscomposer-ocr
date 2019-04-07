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

//    public function display()
    public function initContent()
    {

        if (Module::isInstalled('jscomposer') && (bool) Module::isEnabled('jscomposer'))
        {
            if(isset($this->cms->content)){
                $instance = Module::getInstanceByName('jscomposer');
                $instance->init();
                $this->cms->content = $instance->do_shortcode( $this->cms->content );
                //$this->cms->content = \JsComposer::do_shortcode( $this->cms->content );
                if(vc_mode() === 'page_editable'){
                    //$this->cms->content = call_user_func(\JsComposer::$front_editor_actions['vc_content'], $this->cms->content);
                    $this->cms->content = call_user_func($instance->getInnerActions('vc_content'), $this->cms->content);
                }
            }
        }
        /*if (Module::isInstalled('smartshortcode') && (bool) Module::isEnabled('smartshortcode'))
        {
            if(isset($this->cms->content)){
                $this->cms->content = smartshortcode::do_shortcode( $this->cms->content );
            }
        }*/
        //return parent::initContent();
        parent::initContent();
    }
}
