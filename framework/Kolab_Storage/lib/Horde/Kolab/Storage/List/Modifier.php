<?php
/**
 * Handles all modifications of a Horde_Kolab_Storage_List.
 *
 * PHP version 5
 *
 * @category Kolab
 * @package  Kolab_Storage
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link     http://pear.horde.org/index.php?package=Kolab_Storage
 */

/**
 * Handles all modifications of a Horde_Kolab_Storage_List.
 *
 * Copyright 2004-2012 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (LGPL). If you
 * did not receive this file, see http://www.horde.org/licenses/lgpl21.
 *
 * @category Kolab
 * @package  Kolab_Storage
 * @author   Gunnar Wrobel <wrobel@pardus.de>
 * @license  http://www.horde.org/licenses/lgpl21 LGPL 2.1
 * @link     http://pear.horde.org/index.php?package=Kolab_Storage
 */
class Horde_Kolab_Storage_List_Modifier
{
    /**
     * The list of registered queries.
     *
     * @var Horde_Kolab_Storage_List_Queries
     */
    private $_queries;

    /**
     * The driver for accessing the Kolab storage system.
     *
     * @var Horde_Kolab_Storage_Driver
     */
    private $_driver;

    /**
     * Constructor.
     *
     * @param Horde_Kolab_Storage_Driver       $driver  The primary connection driver.
     * @param Horde_Kolab_Storage_List_Queries $queries The queries listening to the list.
     */
    public function __construct(Horde_Kolab_Storage_Driver $driver,
                                Horde_Kolab_Storage_List_Queries $queries)
    {
        $this->_driver  = $driver;
        $this->_queries = $queries;
    }

    /**
     * Create a new folder.
     *
     * @param string $folder The path of the folder to create.
     * @param string $type   An optional type for the folder.
     *
     * @return NULL
     */
    public function createFolder($folder, $type = null)
    {
        $this->_driver->create($folder);
        if ($type) {
            $this->_driver->setAnnotation(
                $folder, Horde_Kolab_Storage_List_Base::ANNOTATION_FOLDER_TYPE, $type
            );
        }
        $this->_queries->updateAfterCreateFolder($folder);
    }

    /**
     * Delete a folder.
     *
     * WARNING: Do not use this call in case there is still data present in the
     * folder. You are required to empty any data set *before* removing the
     * folder. Otherwise there is no guarantee you can adhere to that Kolab
     * specification that might require the triggering of remote systems to
     * inform them about the removal of the folder.
     *
     * @param string $folder The path of the folder to delete.
     *
     * @return NULL
     */
    public function deleteFolder($folder)
    {
        $this->_driver->delete($folder);
        $this->_queries->updateAfterDeleteFolder($folder);
    }

    /**
     * Rename a folder.
     *
     * @param string $old The old path of the folder.
     * @param string $new The new path of the folder.
     *
     * @return NULL
     */
    public function renameFolder($old, $new)
    {
        $this->_driver->rename($old, $new);
        $this->_queries->updateAfterRenameFolder($old, $new);
    }
}