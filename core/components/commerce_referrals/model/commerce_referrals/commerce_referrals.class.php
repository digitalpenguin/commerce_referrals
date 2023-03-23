<?php
/**
 * The main Commerce_Referrals service class.
 *
 * @package commerce_referrals
 */
class Commerce_Referrals
{
    public $modx = null;
    public $commerce = null;
    public string $namespace = 'commerce_referrals';
    public array $options = [];

    /**
     * @param modX $modx
     * @param array $options
     */
    public function __construct(modX &$modx, array $options = [])
    {
        $this->modx =& $modx;
        $this->namespace = $this->getOption('namespace', $options, 'commerce_referrals');

        $corePath = $this->getOption('core_path', $options, $this->modx->getOption('core_path', null, MODX_CORE_PATH) . 'components/commerce_referrals/');
        $assetsPath = $this->getOption('assets_path', $options, $this->modx->getOption('assets_path', null, MODX_ASSETS_PATH) . 'components/commerce_referrals/');
        $assetsUrl = $this->getOption('assets_url', $options, $this->modx->getOption('assets_url', null, MODX_ASSETS_URL) . 'components/commerce_referrals/');

        /* loads some default paths for easier management */
        $this->options = array_merge([
            'namespace' => $this->namespace,
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'chunksPath' => $corePath . 'elements/chunks/',
            'snippetsPath' => $corePath . 'elements/snippets/',
            'templatesPath' => $corePath . 'templates/',
            'assetsPath' => $assetsPath,
            'assetsUrl' => $assetsUrl,
            'jsUrl' => $assetsUrl . 'js/',
            'cssUrl' => $assetsUrl . 'css/',
            'connectorUrl' => $assetsUrl . 'connector.php'
        ], $options);

        $this->commerce = $this->modx->getService('commerce', 'Commerce', MODX_CORE_PATH . 'components/commerce/model/commerce/');
        if (!($this->commerce instanceof Commerce)) $this->modx->log(1, 'Couldn\'t load commerce');
        $this->commerce->adapter->loadLexicon('commerce:default');

        $this->commerce->adapter->loadPackage('commerce_referrals', $this->getOption('modelPath'));
        $this->commerce->adapter->loadLexicon('commerce_referrals:default');
    }

    /**
     * Get a local configuration option or a namespaced system setting by key.
     *
     * @param string $key The option key to search for.
     * @param array $options An array of options that override local options.
     * @param mixed $default The default value returned if the option is not found locally or as a
     * namespaced system setting; by default this value is null.
     * @return mixed The option value or the default value specified.
     */
    public function getOption(string $key, array $options = [], $default = null)
    {
        $option = $default;
        if (!empty($key)) {
            if ($options != null && array_key_exists($key, $options)) {
                $option = $options[$key];
            }
            elseif (array_key_exists($key, $this->options)) {
                $option = $this->options[$key];
            }
            elseif (array_key_exists("{$this->namespace}.{$key}", $this->modx->config)) {
                $option = $this->modx->getOption("{$this->namespace}.{$key}");
            }
        }
        return $option;
    }
}