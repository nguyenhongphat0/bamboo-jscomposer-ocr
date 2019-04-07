<?php
class ProductController extends ProductControllerCore
{
    public function initContent()
    {
        if (Module::isInstalled('jscomposer') && (bool) Module::isEnabled('jscomposer'))
        {
            if(isset($this->product->description)){
                $instance = Module::getInstanceByName('jscomposer');
                $instance->init();
                $this->product->description = $instance->do_shortcode( $this->product->description );
                if(vc_mode() === 'page_editable'){
                    $this->product->description = call_user_func($instance->getInnerActions('vc_content'), $this->product->description);
                }
            }
        }
        parent::initContent();
    }
}
