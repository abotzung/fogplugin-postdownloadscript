<?php
/**
 * Manager class for PostDownloadScript
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
 * Manager class for PostDownloadScript
 *
 * @category PostDownloadScript
 * @package  FOGProject
 * @author   Alexandre BOTZUNG <alexandre.botzung@grandest.fr>
 * @license  http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link     https://github.com/abotzung/fogplugin-postdownloadscript
 */
class PostdownloadscriptManager extends FOGManagerController
{
    /**
     * The base table name.
     *
     * @var string
     */
    public $tablename = 'postdownloadscript';
    /**
     * Perform the database and plugin installation
     *
     * @return bool
     */
    public function install()
    {
        $this->uninstall();
        $sql = Schema::createTable(
            $this->tablename,
            true,
            array(
                'pdsID',
                'pdsName',
                'pdsDesc',
				'pdsPriority',
                'pdsScript',
				'pdsImageAssociated'
            ),
            array(
                'INTEGER',
                'VARCHAR(255)',
                'LONGTEXT',
				'TINYINT',
                'LONGTEXT',
				'INTEGER'
            ),
            array(
                false,
                false,
                false,
                false,
				false,
				false
            ),
            array(
                false,
                false,
                false,
                false,
				false,
				false
            ),
            array(
                'pdsID'
            ),
            'MyISAM',
            'utf8',
            'pdsID',
            'pdsID'
        );
        return self::$DB->query($sql);
    }
}
