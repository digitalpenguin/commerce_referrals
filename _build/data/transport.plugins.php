<?php

$plugins = array();
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id',1);
$plugins[0]->set('name','getReferenceParam');
$plugins[0]->set('description','Gets the referrer token from the "ref" url parameter.');
$plugins[0]->set('plugincode', getSnippetContent($sources['plugins'] . 'getreferenceparam.plugin.php'));
$plugins[0]->set('category', 0);
$events[0] = $modx->newObject('modPluginEvent');
$events[0]->fromArray(array(
    'event' => 'OnLoadWebDocument',
    'priority' => 0,
    'propertyset' => 0,
),'',true,true);
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO,'Packaged in '.count($events).' onLoadWebDocument event for getReferenceParam plugin for Commerce_Referrals.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR,'Could not find onLoadWebDocument event for getReferenceParam plugin!');
}
unset($events);
return $plugins;