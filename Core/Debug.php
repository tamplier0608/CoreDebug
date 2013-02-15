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
 * Time: 22:45
 *
 */

class Core_Debug extends Core_Debug_Abstract
{
    private static $_critErrors = array(
        E_USER_ERROR,
        E_ERROR,
        E_PARSE,
        E_CORE_ERROR,        
        E_COMPILE_ERROR,
        
    );
    
    private static $_warnings = array(
        E_CORE_WARNING,
        E_COMPILE_WARNING,
    );
    
    private static $_notices = array(
        E_USER_NOTICE,
        E_NOTICE,
        2048
        
    );

    final public function init()
    {
        /* turn off all errors */
        error_reporting(0);
        
        /* set user error handler function */
        register_shutdown_function(array('Core_Debug', 'errorHandler'));
    }
 
    public static function errorHandler()
    {
        $lastError = error_get_last();

        if (!is_null($lastError)) {
            if (in_array($lastError['type'], self::$_critErrors)) {
                
                Core_Debug::getInstance()->addEvent($lastError['message'], 'PHP', 
                        self::CD_ERROR , $lastError['file'], $lastError['line']);
                
            } else if (in_array($lastError['type'], self::$_warnings)) {
                
                Core_Debug::getInstance()->addEvent($lastError['message'], 'PHP', 
                        self::CD_WARNING , $lastError['file'], $lastError['line']);
                
            } else if (in_array($lastError['type'], self::$_notices)) {
                
                Core_Debug::getInstance()->addEvent($lastError['message'], 'PHP', 
                        self::CD_NOTICE , $lastError['file'], $lastError['line']);
                
            } else {
                 Core_Debug::getInstance()->addEvent($lastError['message'], 'Unknown error: ' . $lastError['type'], 
                        self::CD_ERROR , $lastError['file'], $lastError['line']);
            }
        }
        if (!in_array($lastError['type'], self::$_critErrors)) {
            Core_Debug::getInstance()->showLogs();
        } else {
            // @TODO add action
        }
        
        return true;
    } 
    
    /**
     * Get instance of Debugger
     *
     * @access public static
     * @return Core_Debug
     */
    final public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new Core_Debug();
        }
        return self::$_instance;
    }
    
}
