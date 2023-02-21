<?php
/**
 * The PostDownloadScript page. (frontend)
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
 * The PostDownloadScript page. (frontend)
 *
 * @category PostDownloadScript
 * @package  FOGProject
 * @author   Alexandre BOTZUNG <alexandre.botzung@grandest.fr>
 * @license  http://opensource.org/licenses/gpl-3.0 GPLv3
 * @link     https://github.com/abotzung/fogplugin-postdownloadscript
 */
class PostdownloadscriptManagementPage extends FOGPage
{
    /**
     * The node this page displays with.
     *
     * @var string
     */
    public $node = 'postdownloadscript';
    /**
     * Initializes the PostDownloadScript Page.
     *
     * @param string $name The name to pass with.
     *
     * @return void
     */
    public function __construct($name = '')
    {
        $this->name = _('Manage post-download scripts');
        /*self::$foglang['ExportPostdownloadscript'] = _('Exporter la liste');
        self::$foglang['ImportPostdownloadscript'] = _('Importer la liste');*/
        parent::__construct($this->name);
        $this->menu = array(
            'list' => '🧾 ' . _('List all scripts'),
            'add' => '🔆 ' . _('Add a script'),
			'export' => '↪️ ' . _('Export list'),
			'import' => '↩️ ' . _('Import list'),
        );
        global $id;
        if ($id) { //Si on est dans le menu "général"
            $this->subMenu = array(
                "$this->linkformat#pdsgeneral" => self::$foglang['General'],
                $this->delformat => self::$foglang['Delete'],
            );
            $this->notes = array( /* La popup note dans la page "général" du script */
                _('') => $this->obj->get('name'),
                _('Description') => $this->obj->get('description'),
            );
        }
        $this->headerData = array( /* Le listing du tableau dans le node */
            '<input type="checkbox" name="toggle-checkbox" class='
            . '"toggle-checkboxAction" unchecked/>',
            _('Script name'),
            _('Description'),
			_('Priority'),
			_('Associated image')
        );
        $this->templates = array(
            '<label for="toggler">'
            . '<input type="checkbox" name="postdownloadscript[]" value='
            . '"${id}" class="toggle-action" unchecked/>'
            . '</label>',
            '<a href="?node=postdownloadscript&sub=edit&id=${id}" title="'
            . _('Edit')
            . '">${name}</a>',
            '${description}',
			'${priority}',
            '<small><a href="?node=image&sub=edit&id=${image_id}">'
            . '${image_name}</a></small>'
        );
		
		
        $this->attributes = array(
            array(
                'class' => 'filter-false',
                'width' => '16'
            ),
            array(),
            array()
        );
        /**
         * Lambda function to return data either by list or search.
         *
         * @param object $PostDownloadScriptSearch the object to use
         *
         * @return void
         */
        self::$returnData = function (&$PostDownloadScriptSearch) {
			
			// C'est dégueulasse, mais ça fait le job ^^
			if( ($PostDownloadScriptSearch->quelimage != 0) && (FOGCore::getClass('ImageManager')->count()>0) ) {
                foreach ((array)self::getClass('ImageManager')
                    ->find(array('id' => $PostDownloadScriptSearch->quelimage,'isEnabled' => 1)) as &$Image) {
						$temp=$Image->get('name');
						unset($Image);
				}
			}
       
            $this->data[] = array(
                'id'    => $PostDownloadScriptSearch->id,
                'name'  => $PostDownloadScriptSearch->name,
                'description' => $PostDownloadScriptSearch->description,
				'priority' => $PostDownloadScriptSearch->priority,
                'image_id' => $PostDownloadScriptSearch->quelimage,
                'image_name' => $temp
            );
            unset($PostDownloadScriptSearch);
			unset($temp);
        };
    }
    /**
     * Cette fonction intervient dans la page d'ajout d'une ressource
     *
     * @return void
     */
    public function add()
    {
        $this->title = _('New script');
        unset($this->headerData);
        $this->attributes = array(
            array('class' => 'col-xs-4'),
            array('class' => 'col-xs-8 form-group'),
        );
        $this->templates = array(
            '${field}',
            '${input}',
        );
        $fields = array(
            '<label for="name">'
            . _('Name of the script')
            . '</label>' => '<div class="input-group">'
            . '<input class="form-control pds-name" type='
            . '"text" name="name" id="name" required value=""/>'
            . '</div>',
			
			
            '<label for="description">'
            . _('Description')
            . '</label>' => '<div class="input-group">'
            . '<textarea class="form-control pds-description" type='
            . '"text" name="description" id="description">'
            . '</textarea>',

            '<label for="priority">'
            . _('Execution priority (between 0 and 127)')  . '</br>'
			. '<small>' . _('Lower executed first') . '</small>'
            . '</label>' => '<div class="input-group">'
            . '<input class="form-control" type='
            . '"number" name="priority" id="priority" required value="0"/>',
			
            '<label for="quelimage">'
            . _('Associate with image') . '</br>'
			. '<small>' . _('(For disabling script, select - Please select...)') . '</small>'
            . '</label>' => '<div class="input-group">'
            . self::getClass('ImageManager')->buildSelectBox($quelimage,'quelimage','id')
            . '</div>',		
			
            '<label for="lescript">'
            . _('The script') . '</br>'
			. '<small>' . _('(press CTRL+Space for autocompletion)')  . '</small></br>'
			. '<small>' . _('(F11 for fullscreen mode)')  . '</small></br>'
            . '</label>' => '<div class="input-group">'
            . '<textarea class="form-control lescript" type='
            . '"text" name="lescript" id="lescript" autocomplete="off">'
            . '</textarea>',

            '<label for="add">'
            . _('Make Changes?')
            . '</label>' => '<button class="btn btn-info btn-block" name="'
            . 'add" id="add" type="submit">'
            . _('Create script')
            . '</button>'
        );
        array_walk($fields, $this->fieldsToData);
        unset($fields);
        echo '<div class="col-xs-9">';
        echo '<div class="panel panel-info">';
        echo '<div class="panel-heading text-center">';
        echo '<h4 class="title">';
        echo $this->title;
        echo '</h4>';
        echo '</div>';
        echo '<div class="panel-body">';
        echo '<form name="MonForm" class="form-horizontal" method="post" action="'
            . $this->formAction
            . '">';
        $this->render(12);

        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
    }
    /**
     * Actually create the items.
     *
     * @return void
     */
    public function addPost()
    {
        $name = trim(
            filter_input(INPUT_POST, 'name')
        );
        $description = trim(
            filter_input(INPUT_POST, 'description')
        );
        $priority = trim(
            filter_input(INPUT_POST, 'priority')
        );
		$lescript = trim(
            filter_input(INPUT_POST, 'lescript')
        );
		$quelimage = trim(
            filter_input(INPUT_POST, 'quelimage')
        );		
		
        try {
            if (!$name) {
                throw new Exception(
                    _('A name is required!')
                );
            }
            if (self::getClass('PostdownloadscriptManager')->exists($name)) {
                throw new Exception(
                    _('A script already exist with this name!')
                );
            }
			if($priority>127 || $priority<0) {
                throw new Exception(
					_('Execution priority must be between 0 and 127!')
                );
			}
            $PDScript = self::getClass('Postdownloadscript')
                ->set('name', $name)
                ->set('description', $description)
				->set('priority', $priority)
				->set('lescript', $lescript)
				->set('quelimage', $quelimage);
				
            if (!$PDScript->save()) {
                throw new Exception(_('Error when saving script !'));
            }
            $msg = json_encode(
                array(
                    'msg' => _('Script added with success.'),
                    'title' => _('Success')
                )
            );
        } catch (Exception $e) {
            $msg = json_encode(
                array(
                    'error' => _('Error during script creation!'),
                    'title' => $e->getMessage()
                )
            );
        }
        unset($PDScript);
        echo $msg;
        exit;
    }
    /**
     * PostDownloadScript General tab.
     *
     * @return void
     */
    public function PDSEdit()
    {
        unset(
            $this->form,
            $this->data,
            $this->headerData,
            $this->attributes,
            $this->templates
        );
        $name = filter_input(INPUT_POST, 'name') ?:
            $this->obj->get('name');
        $description = filter_input(INPUT_POST, 'description') ?:
            $this->obj->get('description');
        $priority = filter_input(INPUT_POST, 'priority') ?:
            $this->obj->get('priority');
        $lescript = filter_input(INPUT_POST, 'lescript') ?:
            $this->obj->get('lescript');
		$quelimage = filter_input(INPUT_POST, 'quelimage') ?:
			$this->obj->get('quelimage');
			
        $this->title = _('Post-Download script edition : '.$name);
        $this->attributes = array(
            array('class' => 'col-xs-4'),
            array('class' => 'col-xs-8 form-group'),
        );
        $this->templates = array(
            '${field}',
            '${input}',
        );
        $fields = array(
            '<label for="name">'
            . _('Name of the script')
            . '</label>' => '<div class="input-group">'
            . '<input class="form-control pds-name" type='
            . '"text" name="name" id="name" required value="'
			. $name
            . '"/>'
            . '</div>',
			
			
            '<label for="description">'
            . _('Description')
            . '</label>' => '<div class="input-group">'
            . '<textarea class="form-control pds-description" type='
            . '"text" name="description" id="description">'
			. $description
            . '</textarea>',

            '<label for="priority">'
            . _('Execution priority (between 0 and 127)')  . '</br>'
			. '<small>' . _('Lower executed first') . '</small>'
            . '</label>' => '<div class="input-group">'
            . '<input class="form-control" type='
            . '"number" name="priority" id="priority" required value="'
            . $priority
            . '"/>',
			
            '<label for="quelimage">'
            . _('Associate with image') . '</br>'
			. '<small>' . _('(For disabling script, select - Please select...)') . '</small>'
            . '</label>' => '<div class="input-group">'
            . self::getClass('ImageManager')->buildSelectBox($quelimage,'quelimage','id')
            . '</div>',		
			
            '<label for="lescript">'
            . _('The script') . '</br>'
			. '<small>' . _('(press CTRL+Space for autocompletion)')  . '</small></br>'
			. '<small>' . _('(F11 for fullscreen mode)')  . '</small></br>'
            . '</label>' => '<div class="input-group">'
            . '<textarea class="form-control lescript" type='
            . '"text" name="lescript" id="lescript" autocomplete="off">'
			. $lescript
            . '</textarea>',

            '<label for="updategen">'
            . _('Update?')
            . '</label>' => '<button class="btn btn-info btn-block" name="'
            . 'updategen" id="updategen" type="submit">'
            . _('Update')
            . '</button>'
        );
        array_walk($fields, $this->fieldsToData);

        unset($fields);
        echo '<!-- General -->';
		
        echo '<div class="tab-pane fade in active" id="pdsgeneral">';
        echo '<div class="panel panel-info">';
        echo '<div class="panel-heading text-center">';
        echo '<h4 class="title">';
        echo $this->title;
        echo '</h4>';
        echo '</div>';
        echo '<div class="panel-body">';
        echo '<form name="MonForm" class="form-horizontal" method="post" action="'
            . $this->formAction
            . '&tab=pdsgeneral">';
        $this->render(12);
        echo '</form>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        unset(
            $this->form,
            $this->data,
            $this->headerData,
            $this->attributes,
            $this->templates
        );
    }
    /**
     * Edit the current item.
     *
     * @return void
     */
    public function edit()
    {
        echo '<div class="col-xs-9 tab-content">';
        $this->PDSEdit();
        echo '</div>';
    }
    /**
     * PostDownloadScript General Post()
     *
     * @return void
     */
    public function PDSGeneralPost()
    {
        $name = filter_input(INPUT_POST, 'name') ?:
            $this->obj->get('name');
        $description = filter_input(INPUT_POST, 'description');
        $priority = filter_input(INPUT_POST, 'priority') ?:
            $this->obj->get('priority');
        $lescript = filter_input(INPUT_POST, 'lescript');
		$quelimage = filter_input(INPUT_POST, 'quelimage');
		
        if ($this->obj->get('name') != $name
            && self::getClass('PostdownloadscriptManager')->exists(
                $name,
                $this->obj->get('id')
            )
        ) {
            throw new Exception(
                _('A script already exist with this name!')
            );
        }
		if($priority>127 || $priority<0) {
           throw new Exception(
              _('Execution priority must be between 0 and 127!')
           );
		}
        $this->obj
            ->set('name', $name)
            ->set('description', $description)
			->set('priority', $priority)
			->set('lescript', $lescript)
			->set('quelimage', $quelimage);
    }
    /**
     * Submit the edits.
     *
     * @return void
     */
    public function editPost()
    {
        global $tab;
        try {
            switch ($tab) {
            case 'pdsgeneral':
                $this->PDSGeneralPost();
                break;
            }
            if (!$this->obj->save()) {
                throw new Exception(_('Post-download script update failed!'));
            }
            //$hook = 'BROADCAST_UPDATE_SUCCESS';
            $msg = json_encode(
                array(
                    'msg' => _('Update success.'),
                    'title' => _('Update success')
                )
            );
        } catch (Exception $e) {
            //$hook = 'BROADCAST_UPDATE_FAIL';
            $msg = json_encode(
                array(
                    'error' => $e->getMessage(),
                    'title' => _('Error during script update!')
                )
            );
        }
        echo $msg;
        exit;
    }
}
