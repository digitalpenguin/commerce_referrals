<?php
/* Get the core config */
$componentPath = dirname(__DIR__);
if (!file_exists($componentPath.'/config.core.php')) {
    die('ERROR: missing '.$componentPath.'/config.core.php file defining the MODX core path.');
}

echo "<pre>";
/* Boot up MODX */
echo "Loading modX...\n";
require_once $componentPath . '/config.core.php';
require_once MODX_CORE_PATH . 'model/modx/modx.class.php';
$modx = new modX();
echo "Initializing manager...\n";
$modx->initialize('mgr');
$modx->getService('error','error.modError', '', '');
$modx->setLogTarget('HTML');



/* Namespace */
if (!createObject('modNamespace',array(
    'name' => 'commerce_referrals',
    'path' => $componentPath.'/core/components/commerce_referrals/',
    'assets_path' => $componentPath.'/assets/components/commerce_referrals/',
),'name', false)) {
    echo "Error creating namespace commerce_referrals.\n";
}

/* Path settings */
if (!createObject('modSystemSetting', array(
    'key' => 'commerce_referrals.core_path',
    'value' => $componentPath.'/core/components/commerce_referrals/',
    'xtype' => 'textfield',
    'namespace' => 'commerce_referrals',
    'area' => 'Paths',
    'editedon' => time(),
), 'key', false)) {
    echo "Error creating commerce_referrals.core_path setting.\n";
}

if (!createObject('modSystemSetting', array(
    'key' => 'commerce_referrals.assets_path',
    'value' => $componentPath.'/assets/components/commerce_referrals/',
    'xtype' => 'textfield',
    'namespace' => 'commerce_referrals',
    'area' => 'Paths',
    'editedon' => time(),
), 'key', false)) {
    echo "Error creating commerce_referrals.assets_path setting.\n";
}

/* Fetch assets url */
$requestUri = $_SERVER['REQUEST_URI'] ?: __DIR__ . '/_bootstrap/index.php';
$bootstrapPos = strpos($requestUri, '_bootstrap/');
$requestUri = rtrim(substr($requestUri, 0, $bootstrapPos), '/').'/';
$assetsUrl = "{$requestUri}assets/components/commerce_referrals/";

if (!createObject('modSystemSetting', array(
    'key' => 'commerce_referrals.assets_url',
    'value' => $assetsUrl,
    'xtype' => 'textfield',
    'namespace' => 'commerce_referrals',
    'area' => 'Paths',
    'editedon' => time(),
), 'key', false)) {
    echo "Error creating commerce_referrals.assets_url setting.\n";
}


$settings = include dirname(dirname(__FILE__)).'/_build/data/settings.php';
foreach ($settings as $key => $opts) {
    $val = $opts['value'];

    if (isset($opts['xtype'])) $xtype = $opts['xtype'];
    elseif (is_int($val)) $xtype = 'numberfield';
    elseif (is_bool($val)) $xtype = 'modx-combo-boolean';
    else $xtype = 'textfield';

    if (!createObject('modSystemSetting', array(
        'key' => 'commerce_referrals.' . $key,
        'value' => $opts['value'],
        'xtype' => $xtype,
        'namespace' => 'commerce_referrals',
        'area' => $opts['area'],
        'editedon' => time(),
    ), 'key', false)) {
        echo "Error creating commerce_referrals.".$key." setting.\n";
    }
}

if (!createObject('modPlugin', [
    'name' => 'Commerce_Referrals',
    'static' => true,
    'static_file' => $componentPath . '/core/components/commerce_referrals/elements/plugins/getreferenceparam.plugin.php',
], 'name', true)) {
    echo "Error creating Commerce_Referrals Plugin.\n";
}
$plugin = $modx->getObject('modPlugin', ['name' => 'Commerce_Referrals']);
if ($plugin) {
    if (!createObject('modPluginEvent', [
        'pluginid' => $plugin->get('id'),
        'event' => 'OnLoadWebDocument',
        'priority' => 0,
    ], ['pluginid','event'], false)) {
        echo "Error creating modPluginEvent.\n";
    }
}

$path = $modx->getOption('commerce.core_path', null, MODX_CORE_PATH . 'components/commerce/') . 'model/commerce/';
$params = ['mode' => $modx->getOption('commerce.mode')];
/** @var Commerce|null $commerce */
$commerce = $modx->getService('commerce', 'Commerce', $path, $params);
if (!($commerce instanceof Commerce)) {
    die("Couldn't load Commerce class");
}


// Make sure our module can be loaded. In this case we're using a composer-provided PSR4 autoloader.
include $componentPath . '/core/components/commerce_referrals/vendor/autoload.php';

// Grab the path to our namespaced files
$modulePath = $componentPath . '/core/components/commerce_referrals/src/Modules/';

// Instruct Commerce to load modules from our directory, providing the base namespace and module path twice
$commerce->loadModulesFromDirectory($modulePath, 'DigitalPenguin\\Referrals\\Modules\\', $modulePath);


//$modx->addPackage('commerce_referrals', $componentPath.'/core/components/commerce_referrals/model/');
$manager= $modx->getManager();
$manager->createObjectContainer(\CommerceReferralsReferrer::class);
$manager->createObjectContainer(\CommerceReferralsReferral::class);

// Clear the cache
$modx->cacheManager->refresh();

echo "Done.";


/**
 * Creates an object.
 *
 * @param string $className
 * @param array $data
 * @param string $primaryField
 * @param bool $update
 * @return bool
 */
function createObject ($className = '', array $data = array(), $primaryField = '', $update = true) {
    global $modx;
    /* @var xPDOObject $object */
    $object = null;

    /* Attempt to get the existing object */
    if (!empty($primaryField)) {
        if (is_array($primaryField)) {
            $condition = array();
            foreach ($primaryField as $key) {
                $condition[$key] = $data[$key];
            }
        }
        else {
            $condition = array($primaryField => $data[$primaryField]);
        }
        $object = $modx->getObject($className, $condition);
        if ($object instanceof $className) {
            if ($update) {
                $object->fromArray($data);
                return $object->save();
            } else {
                $condition = $modx->toJSON($condition);
                echo "Skipping {$className} {$condition}: already exists.\n";
                return true;
            }
        }
    }

    /* Create new object if it doesn't exist */
    if (!$object) {
        $object = $modx->newObject($className);
        $object->fromArray($data, '', true);
        return $object->save();
    }

    return false;
}
