<?php

if (!defined('_PS_VERSION_'))
	exit;

function upgrade_module_4_3_13($object)
{
    $object->registerHook('VcShortcodesCssClass');
    return true;
}