<?php
class jscomposerTestModuleFrontController extends ModuleFrontController
{
    public function init()
    {
        $this->display_column_left = false;
        $this->display_column_right = false;
        $id_cms = Tools::getvalue('id_cms');
        $this->display_header = true;
        $this->display_footer = true;
        $this->controller_type = 'admin';
        parent::init();
    }
    public function initContent()
    {
        if (Module::isInstalled('jscomposer') && (bool) Module::isEnabled('jscomposer'))
        {
            $instance = Module::getInstanceByName('jscomposer');
            $instance->init();
            $content = $content = JsComposer::getCurrentContent();
            $content = $instance->do_shortcode( $content );
            if(vc_mode() === 'page_editable'){
                $content = $instance->getVcInlineTag($content);
            }
        }

        parent::initContent();


        $this->context->smarty->assign(array(
            'content' => $content,
        ));

        $this->setTemplate('module:jscomposer/views/templates/front/page.tpl');
    }
}