<?php
/**
 * PostDownloadScript Class handler
 *
 * PHP version 5
 *
 * @category PostDownloadScript
 * @package  FOGProject
 * @author   Alexandre BOTZUNG <alexandre.botzung@grandest.fr>
 * @license  http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link     https://github.com/abotzung/fogplugin-postdownloadscript
 */
/**
 * PostDownloadScript Class handler
 *
 * @category PostDownloadScript
 * @package  FOGProject
 * @author   Alexandre BOTZUNG <alexandre.botzung@grandest.fr>
 * @license  http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link     https://github.com/abotzung/fogplugin-postdownloadscript
 */
class Postdownloadscript extends FOGController
{
    /**
     * The Postdownloadscript table
     *
     * @var string
     */
    protected $databaseTable = 'postdownloadscript';
    /**
     * The Postdownloadscript fields and common names
     *
     * 
     */
    protected $databaseFields = array(
        'id' => 'pdsID',
        'name' => 'pdsName',
        'description' => 'pdsDesc',
        'priority' => 'pdsPriority',
		'lescript' => 'pdsScript',
		'quelimage' => 'pdsImageAssociated'
    );
    /**
     * The required fields.
     *
     * @var array
     */
    protected $databaseFieldsRequired = array(
        'name',
    );
}
