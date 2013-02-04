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
class Core_Debug_DebugAbstract extends Zend_Debug
{
    const CD_INFORMATION = 'info';
    const CD_WARNING = 'warning';
    const CD_ERROR = 'error';
    const CD_NOTICE = 'notice';

    /**
     * @var Core_Debug object
     * @access protected
     *
     * Instance of Core_Debug class
     */
    protected static $_instance = null;

    /**
     * @var array $logData
     *
     * Array with events data
     */
    protected $_logData;

    protected $_logEnv = array(
        'development',
        'testing',
    );

    /**
     * Constructor
     *
     * @access protected
     */
    protected function __construct()
    {
        $this->_logData = array();
    }

    /**
     * Get instance of Debugger
     *
     * @access public static
     * @return Core_Debug
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Core_Debug();
        }
        return self::$_instance;
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
    public function addEvent($type = Core_Debug::CD_NOTICE, $value, $key = '')
    {
        $backTrace = debug_backtrace();

        $file = $backTrace[0]['file'];
        $line = $backTrace[0]['line'];

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
        $this->addEvent(Core_Debug::CD_INFORMATION, $key, $value);
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
        if (in_array(APPLICATION_ENV, $this->_logEnv) && count($this->_logData)) {
            /**
             *  include view of debugger
             */
            require_once 'logger.phtml';
        }
    }
}
