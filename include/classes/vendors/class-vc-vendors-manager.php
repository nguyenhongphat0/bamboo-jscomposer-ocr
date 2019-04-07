<?php
/**
 * Vendors manager to load required classes and functions to work with VC.
 */
Class Vc_Vendors_Manager {
	protected $vendors = array();
	function __construct($editor) {            
//		add_action('vc_before_init_base', array(&$this, 'init'));
            call_user_func(array(&$this, 'init'),$editor);
	}
	public function init($editor) {
		require_once $editor->vc_path_dir('VENDORS_DIR', '_autoload.php');                  
		$this->load();
	}
	public function add(Vc_Vendor_Interface $vendor) {
		$this->vendors[] = $vendor;
	}
	public function load() { 

		foreach($this->vendors as $vendor) {
			$vendor->load();
		}
	}
}
