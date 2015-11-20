<?php
function pre_install()
{
    global $sugar_config;
    $sugarVersion = isset($sugar_config['suitecrm_version']) ? 'Suite'.$sugar_config['suitecrm_version'] : $sugar_config['sugar_version'];
    if(!is_dir(__DIR__.'/../SugarModules/upgrade_unsafe/'.$sugarVersion)) {
        sugar_die('This version is not supported');
    }
}
