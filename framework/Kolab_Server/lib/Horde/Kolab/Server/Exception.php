<?php
/**
 * A library for accessing the Kolab user database.
 *
 * PHP version 5
 *
 * @category Kolab
 * @package  Kolab_Server
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_Server
 */

/**
 * This class provides the standard error class for Kolab Server exceptions.
 *
 * Copyright 2009 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/lgpl.html.
 *
 * @category Kolab
 * @package  Kolab_Server
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.fsf.org/copyleft/lgpl.html LGPL
 * @link     http://pear.horde.org/index.php?package=Kolab_Server
 */
class Horde_Kolab_Server_Exception extends Horde_Exception
{
    /**
     * Constants to define the error type.
     */
    const SYSTEM              = 1;
    const EMPTY_RESULT        = 2;
    const INVALID_INFORMATION = 3;

    /**
     * The array of available error messages. These are connected to the error
     * codes used above and might be used to differentiate between what we show
     * the user in the frontend and what we actually log in the backend.
     *
     * @var array
     */
    protected $messages;

    /**
     * Exception constructor
     *
     * @param mixed $message The exception message, a PEAR_Error object, or an
     *                       Exception object.
     * @param mixed $code    A numeric error code, or
     *                       an array from error_get_last().
     */
    public function __construct($message = null, $code = null)
    {
        $this->setMessages();

        parent::__construct($message, $code);
    }

    /**
     * Initialize the messages handled by this exception.
     *
     * @return NULL
     */
    protected function setMessages()
    {
        $this->messages = array(
            self::SYSTEM              => _("An internal error occured."),
            self::EMPTY_RESULT        => _("No result was found."),
            self::INVALID_INFORMATION => _("The information provided is invalid."),
        );
    }
}
