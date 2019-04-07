<?php
class  jscomposervc_frontModuleFrontController extends ModuleFrontController
{
	public  $id = -1;
	public function init()
	{
		// print Tools::getValue('controller');
		$this->display_column_left = false;
		$this->display_column_right = false;
		$id_cms = Tools::getvalue('id_cms');
		$this->display_header = false;
		$this->display_footer = false;
		$this->controller_type = 'admin';
		parent::init();
	}
	public function initContent()
	{
		parent::initContent();
		$contenttext = Hook::exec('displayBackOfficeHeader');
		$this->context->controller->addJqueryUI(array('ui.tabs', 'ui.widget', 'ui.sortable', 'ui.droppable', 'ui.draggable', 'ui.accordion', 'ui.autocomplete', 'ui.slider'));
		$contenttext .=  $this->content_text();
		$contenttext .= Hook::exec('displayBackOfficeFooter');
		$this->context->smarty->assign(array(
                                           'content'=>$contenttext,
                                           // 'HOOK_HEADER' => $HOOK_HEADER,
                                           // 'HOOK_FOOTER' => $HOOK_FOOTER
                                            ));
		 $this->setTemplate('content.tpl');
	}
	public function content_text()
    {
        ob_start();
        $Vc_Frontend_Editor = new Vc_Frontend_Editor();
        $Vc_Frontend_Editor->adminInit();
        return ob_get_clean();
    }
	// public function initContent()
	// {
	// 	parent::initContent();
	// 	$this->context->controller->addJqueryUI(array('ui.tabs', 'ui.widget', 'ui.sortable', 'ui.droppable', 'ui.draggable', 'ui.accordion', 'ui.autocomplete', 'ui.slider'));
	// 	$contenttext =  $this->content_text();
	// 	$jsc = new JsComposer();
	// 	$HOOK_HEADER = $jsc->hookdisplayBackOfficeHeader();
	// 	$HOOK_FOOTER = $jsc->hookdisplayBackOfficeFooter();
	// 	$HOOK_HEADER .= Hook::exec('displayHeader');
	// 	$HOOK_FOOTER .= Hook::exec('displayFooter');
	// 	$this->context->smarty->assign(array(
 //                                           'content'=>$contenttext,
 //                                           'HOOK_HEADER' => $HOOK_HEADER,
 //                                           'HOOK_FOOTER' => $HOOK_FOOTER
 //                                            ));
	// 	 $this->setTemplate('content.tpl');
	// }
}