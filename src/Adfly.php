<?php
/**
 * Created by PhpStorm.
 * User: maartenpaauw
 * Date: 06-07-15
 * Time: 18:01
 */

namespace M44rt3np44uw\Adfly;

use Illuminate\Support\Facades\Config;

/**
 * Class Adfly
 *
 * @package M44rt3np44uw\Adfly
 */
class Adfly {

    /**
     * Base url.
     */
    const BASE_URL = "http://api.adf.ly/api.php";

    /**
     * @var
     */
    protected $options;

    /**
     * @var
     */
    protected $url;

    /**
     * @return mixed
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param mixed $options
     */
    public function setOptions($options)
    {
        $this->options = $options;
    }

    /**
     * @param $options
     */
    public function mergeOptions($options)
    {
        if(isset($options) && !empty($options))
        {
            $this->setOptions(array_merge($this->getOptions(), $options));
        }
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     *
     */
    public function __construct()
    {
        // Initialize the options array.
        $this->initOptions();

        // Check key and uid.
        $this->configCheck();
    }

    /**
     * @param       $url
     * @param array $options
     *
     * @return string
     */
    public function create($url, $options = [])
    {
        // URL.
        $this->setUrl($url);

        // Options.
        $options = array_merge($options, ['url' => $this->getUrl()]);

        // Push options.
        $this->mergeOptions($options);

        // Return the Adfly url.
        return $this->getAdflyUrl();
    }

    /**
     * Initialize the options array.
     */
    private function initOptions()
    {
        // Get the config options.
        $config_options = Config::get('adfly');

        // Push the config options to the options array.
        $this->setOptions($config_options);
    }


    /**
     * Config check.
     *
     * @throws \Exception
     */
    private function configCheck()
    {
        $options = $this->getOptions();

        if(!isset($options['key']) || empty($options['key']))
        {
            throw new \Exception("The 'key' option is required and can not be null.");
        }

        if(!isset($options['uid']) || empty($options['uid']))
        {
            throw new \Exception("The 'uid' option is required and can not be null.");
        }
    }

    /**
     * @return string
     */
    private function getApiUrl()
    {
        // Query string
        $query_string = http_build_query($this->getOptions());

        // API url
        $api_url = Adfly::BASE_URL . '?' . $query_string;

        // Return api url.
        return $api_url;
    }

    /**
     * Get the shorten Adfly url.
     *
     * @return string
     */
    private function getAdflyUrl()
    {
        // Get the generated Adfly url.
        return file_get_contents($this->getApiUrl());
    }
}