<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_4_3_20($module)
{
    return $module->registerHook('VcAllowedImgAttrs');
}