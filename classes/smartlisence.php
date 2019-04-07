<?php

class Smartlisence{
     
    
    public function checkUpdate($this_val = null){

        //print_r($this_val);
        if(in_array(Tools::getValue('controller'), array('AdminJsComposerSetting', 'AdminModules')) && Tools::getValue('token')){
            $installed_version = '4.4';
            $timeout = Configuration::get('jscomposer_update_timeout', false);
            $latest_version = Configuration::get('jscomposer_new_version', '4.4');
            //$timeout = 0;
            $now = time();

          //  //checking if update is available------------------
            if($now > (int)$timeout) { // set a timeout condition
                    $timelimit = 30 * 60*60;
                    $jscomposer_key = Configuration::get('jscomposer_purchase_key', false);
                    if(empty($jscomposer_key)){
                        $jscomposer_key = 'empty';
                    }
 
                $curl = curl_init();
                curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://updates.smartdatasoft.net/check_for_updates.php',
                CURLOPT_USERAGENT => 'Smartdatasoft cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                        'purchase_key' => $jscomposer_key,
                        'operation' => 'check_update',
                        'domain' => $_SERVER['HTTP_HOST'],
                        'module' =>  $this_val['module_name'],
                        'version' => $this_val['version'],
                        'theme_name' => basename(_THEME_DIR_),
                )
        ));


                    $resp = curl_exec($curl);
                    curl_close($curl);

                    $respAarray = (array) Tools::jsonDecode($resp);
             
                    if (!empty($respAarray)) {
                      // print_r($respAarray);
                            if ($respAarray['status'] == 1) {
                                    $latest_version =  $respAarray['current_version'];
                                  //  echo  $latest_version ;
                                    Configuration::updateValue('jscomposer_new_version', $latest_version);
                            }
                    }

                    Configuration::updateValue('jscomposer_update_timeout', $now + $timelimit);
            //}
            if(Tools::version_compare($latest_version, $installed_version, '>')){
                    $this->warning = 'New version '.$latest_version.' is available now! Please update now. Installed version is '.$installed_version;
            }
        }
       
       }
    }

    public function deactivateModule($this_val){

         $jsComposerObject = JsComposer::$instance;
            Configuration::updateValue('jscomposer_purchase_key', Tools::getValue('purchase_key'));
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://updates.smartdatasoft.net/activate.php',
                CURLOPT_USERAGENT => 'Smartdatasoft cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'purchase_key' => Tools::getValue('purchase_key'),
                    'operation' => 'deactivate',
                    'domain' => $_SERVER['HTTP_HOST'],
                    'module' => $this_val['module_name'],
                    'version' => $this_val['version'],
                    'theme_name' => basename(_THEME_DIR_)
                )
            ));
            $resp = curl_exec($curl);

            curl_close($curl);

            $respAarray = (array) Tools::jsonDecode($resp);

            if (!empty($respAarray)) {

                if ($respAarray['status'] == 1) {
                    Configuration::updateValue('jscomposer_status', '0');
                    $message =$respAarray['msg'];
                    $jsComposerObject->adminDisplayWarning(html_entity_decode($message));
                }
            } else {
                $message = 'Error while deactivating';
                $jsComposerObject->adminDisplayWarning(html_entity_decode($message));
            }
        
    }
    public function activateModule($this_val=null){
            $jsComposerObject = JsComposer::$instance;
            Configuration::updateValue('jscomposer_purchase_key', Tools::getValue('purchase_key'));

            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://updates.smartdatasoft.net/activate.php',
                CURLOPT_USERAGENT => 'Smartdatasoft cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'purchase_key' => $this_val['purchase_key'],
                    'operation' => 'activate',
                    'domain' => $_SERVER['HTTP_HOST'],
                    'module' => $this_val['module_name'],
                    'version' => $this_val['version'],
                     'theme_name' => basename(_THEME_DIR_)
                )
            ));
            $resp = curl_exec($curl);


            curl_close($curl);


           // print_r($resp);
          
            $respAarray = (array) Tools::jsonDecode($resp);
           
            if (!empty($respAarray)) {
                $message = $respAarray['msg'] ;
                $jsComposerObject->adminDisplayWarning(html_entity_decode($message));
                if ($respAarray['status'] == 1) {
                    Configuration::updateValue('jscomposer_status', '1');
                }
            } else {
                $message = "Activation error";
                $jsComposerObject->adminDisplayWarning(html_entity_decode($message));
            }
       
    }

    public function isActive(){
        return (Configuration::get('jscomposer_status',0))? true : false;
    }

    public function updateModule($this_val){
 
            $jsComposerObject = JsComposer::$instance;
            Configuration::updateValue('jscomposer_purchase_key', Tools::getValue('purchase_key'));
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_RETURNTRANSFER => 1,
                CURLOPT_URL => 'http://updates.smartdatasoft.net/download.php',
                CURLOPT_USERAGENT => 'Smartdatasoft cURL Request',
                CURLOPT_POST => 1,
                CURLOPT_POSTFIELDS => array(
                    'purchase_key' => Tools::getValue('purchase_key'),
                    'operation' => 'update',
                    'module' => $this_val['module_name'],
                    'version' => $this_val['version'],
                    'domain' => $_SERVER['HTTP_HOST'],
                    'theme_name' => basename(_THEME_DIR_)
                )
            ));
            $resp = curl_exec($curl);
            curl_close($curl);

            $respAarray = (array)Tools::jsonDecode($resp);
            if (!empty($respAarray)){
                if ($respAarray['status'] == 1 && isset($respAarray['archive']) && !empty($respAarray['archive'])){
                
                    $file = base64_decode($respAarray['archive']);
                    $new = _PS_MODULE_DIR_ . 'jscomposer/jscomposer.zip';
                    
                    //    header('Content-Description: File Transfer');
                    //    header('Content-Type: application/octet-stream');
                    //    header('Content-Disposition: attachment; filename='.basename($file));
                    //    header('Expires: 0');
                    //    header('Cache-Control: must-revalidate');
                    //    header('Pragma: public');
                    //    header('Content-Length: ' . filesize($file));
                    //    ob_clean();
                    //    flush();
                    //    readfile($file);
                    //    exit;
                    // I am new code...

                    file_put_contents($new, $file);

                    $zip = new ZipArchive;
                    if ($zip->open($new) === TRUE) {
                        $zip->extractTo(_PS_MODULE_DIR_);
                        $zip->close();
                        @unlink($new);
                        $url = Context::getContext()->link->getAdminLink('AdminModules');
                        Tools::redirectAdmin($url);
                    }
                } else {
                    $message = $respAarray['msg'];
                    $jsComposerObject->adminDisplayWarning(html_entity_decode($message));
                }
                
            } else {
                $message ='Error while updating';
                $jsComposerObject->adminDisplayWarning(html_entity_decode($message));
            }

        }


}