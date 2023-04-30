<?php
/**
 * Adds PostDownloadScript menu item.
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
 * Adds PostDownloadScript menu item.
 *
 * @category PostDownloadScript
 * @package  FOGProject
 * @author   Alexandre BOTZUNG <alexandre.botzung@grandest.fr>
 * @license  http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link     https://github.com/abotzung/fogplugin-postdownloadscript
 */
class AddPDSMenuItem extends Hook
{
    /**
     * The name of this hook.
     *
     * @var string
     */
    public $name = 'AddPDSMenuItem';
    /**
     * The description of this hook.
     *
     * @var string
     */
    public $description = 'Add menu and report types for PostDownloadScripts';
    /**
     * The active flag.
     *
     * @var bool
     */
    public $active = true;
    /**
     * The node this hook enacts with.
     *
     * @var string
     */
    public $node = 'postdownloadscript';
    /**
     * Initializes object.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        self::$HookManager
            ->register(
                'MAIN_MENU_DATA',
                array(
                    $this,
                    'menuData'
                )
            )
            ->register(
                'SEARCH_PAGES',
                array(
                    $this,
                    'addSearch'
                )
            )
            ->register(
                'PAGES_WITH_OBJECTS',
                array(
                    $this,
                    'addPageWithObject'
                )
            )
            ->register(
                'REPORT_TYPES',
                array(
                    $this,
                    'reportTypes'
                )
            );

    }
    /**
     * The menu data to change.
     *
     * @param mixed $arguments The arguments to change.
     *
     * @return void
     */
    public function menuData($arguments)
    {
        if (!in_array($this->node, (array)self::$pluginsinstalled)) {
            return;
        }
        self::arrayInsertAfter(
            'storage',
            $arguments['main'],
            $this->node,
            array(
                _('Post-Download Scripts'),
                'fa fa-file-code-o'
            )
        );
    }
    /**
     * Adds the wol page to search elements.
     *
     * @param mixed $arguments The arguments to change.
     *
     * @return void
     */
    public function addSearch($arguments)
    {
        if (!in_array($this->node, (array)self::$pluginsinstalled)) {
            return;
        }
        array_push($arguments['searchPages'], $this->node);
    }
    /**
     * Adds the wol page to objects elements.
     *
     * @param mixed $arguments The arguments to change.
     *
     * @return void
     */
    public function addPageWithObject($arguments)
    {
        if (!in_array($this->node, (array)self::$pluginsinstalled)) {
            return;
        }
        array_push($arguments['PagesWithObjects'], $this->node);
    }
}
