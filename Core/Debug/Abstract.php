<?php

/**
 * Custom debug with logging data
 *
 * @package Core
 * @subpackage Debug
 * @author Sergey Kukunin <tamplier0608@gmail.com>
 * @version 0.1
 * @license GPL
 *
 * @since 1.0
 *
 * Date: 18.01.13
 * Time: 22:37
 *
 * @TODO integrate Zend_Logger
 */
abstract class Core_Debug_Abstract extends Zend_Debug
{
    const CD_INFORMATION = 'info';
    const CD_WARNING = 'warning';
    const CD_ERROR = 'error';
    const CD_NOTICE = 'notice';
        
    /**
     * Instance of Core_Debug class
     *
     * @var Core_Debug
     * @access protected
     */
    protected static $_instance = null; 
    
    /**
     * Array with events data
     *
     * @var array $logData
     */
    protected $_logData;

    /**
     * List of project environments
     * 
     * @var array 
     */
    protected $_logEnv = array(
        'development',
        'testing',
        'staging'
    );

    /**
     *  
     * @var boolean 
     */
    protected $_show = null;
    
    /**
     * Constructor
     *
     * @access protected
     */
    protected function __construct()
    {
        $this->_logData = array();
        $this->_show = true;
    }

    /**
     * Register debug event
     *
     * @access public
     * @param string $type
     * @param mixed|array $value
     * @param string $key
     * @return Core_Debug|null
     */
    public function addEvent($value, $key = '', $type = Core_Debug::CD_INFORMATION, 
            $file = null, $line = null) 
    {
        $backTrace = debug_backtrace();

        if (is_null($file)) {
            $file = $backTrace[0]['file'];
        }
        if (is_null($line)) {
            $line = $backTrace[0]['line'];
        }

        $value = self::dump($value, null, false);

        $this->_logData[] = array(
            'key' => $key,
            'value' => $value,
            'type' => $type,
            'backtrace' => "File: $file<br />Line: $line",
        );
        
        return self::$_instance;
    }

    /**
     * Register event of type Information
     *
     * @access public
     * @param $key
     * @param mixed $value
     * @return Core_Debug
     */
    public function addInfo($value, $key = '')
    {
        $this->addEvent(Core_Debug::CD_INFORMATION, $value, $key);
        return self::$_instance;
    }

    /**
     * Get log data as array
     *
     * @access protected
     * @return array
     */
    protected function getLogData()
    {
        return $this->_logData;
    }

    /**
     * Show logs
     *
     * @access public
     */
    public function showLogs()
    {
        if ((in_array(APPLICATION_ENV, $this->_logEnv) 
                && count($this->_logData)) && $this->_show) {
            
            /**
             *  include view of debugger
             */
            require_once 'logger.phtml';
        }
    }
 
    /**
     * Disable view of output debug
     * 
     * @access public
     * @return \Core_Debug_Abstract 
     */
    public function disableView() {
        $this->_show = false;
        
        return $this;
    }
}

