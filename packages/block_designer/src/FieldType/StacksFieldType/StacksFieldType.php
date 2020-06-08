<?php  namespace Concrete\Package\BlockDesigner\Src\FieldType\StacksFieldType;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Concrete\Package\BlockDesigner\Src\FieldType\FieldType;

class StacksFieldType extends FieldType
{
    protected $ftHandle = 'stacks';
    protected $uses = array('StackList', 'Stack', 'CollectionVersion');

    public function getFieldDescription()
    {
        return t("A stacks field");
    }

    public function getViewContents()
    {
        return '<?php 
if (isset($' . $this->data['slug'] . ') && !empty($' . $this->data['slug'] . ')){ ?>' . $this->data['prefix'] . '<?php  foreach($' . $this->data['slug'] . ' as $' . $this->data['slug'] . '_stack){
        $' . $this->data['slug'] . '_stack->display();
    } ?>' . $this->data['suffix'] . '<?php 
} ?>';
    }

    public function copyFiles()
    {
        $files = array();
        if ($this->data['ft_count'] <= 0) {
            $files[] = array(
                'source' => $this->ftDirectory . 'js' . DIRECTORY_SEPARATOR,
                'target' => $this->data['btDirectory'] . 'js_form' . DIRECTORY_SEPARATOR,
            );
        }
        return $files;
    }

    private function getEntriesTableName()
    {
        return $this->data['btTable'] . ucFirst($this->data['slug']) . 'Entries';
    }

    public function getViewFunctionContents()
    {
        return '$stacks = array();
        if ($stacksEntries = $db->GetAll(\'SELECT * FROM ' . $this->getEntriesTableName($this->data) . ' WHERE bID = ? ORDER BY sortOrder ASC\', array($this->bID))) {
            foreach ($stacksEntries as $stacksEntry) {
                $st = Stack::getByID($stacksEntry[\'stID\']);
                $stacks[$stacksEntry[\'stID\']] = $st;
            }
        }
        $this->set(\'' . $this->data['slug'] . '\', $stacks);';
    }

    public function getAddFunctionContents()
    {
        $lines = array();
        if ($this->data['ft_count'] <= 0) {
            $lines[] = '$this->requireAsset(\'css\', \'select2\');';
            $lines[] = '$this->requireAsset(\'javascript\', \'select2\');';
            $lines[] = '$this->requireAsset(\'javascript\', \'select2sortable\');';
        }
        $lines[] = '$stacksSelected = array();
        $stacksOptions = $this->getStacks();
        $this->set(\'' . $this->data['slug'] . '_options\', $stacksOptions);
        $this->set(\'' . $this->data['slug'] . '_selected\', $stacksSelected);';
        return implode(PHP_EOL . '        ', $lines);
    }

    public function getEditFunctionContents()
    {
        $lines = array();
        if ($this->data['ft_count'] <= 0) {
            $lines[] = '$this->requireAsset(\'css\', \'select2\');';
            $lines[] = '$this->requireAsset(\'javascript\', \'select2\');';
            $lines[] = '$this->requireAsset(\'javascript\', \'select2sortable\');';
        }
        $lines[] = '$stacksSelected = array();
        $ordered = array();
        $stacksOptions = $this->getStacks();
        if ($stacksEntries = $db->GetAll(\'SELECT * FROM ' . $this->getEntriesTableName($this->data) . ' WHERE bID = ? ORDER BY sortOrder ASC\', array($this->bID))) {
            foreach ($stacksEntries as $stacksEntry) {
                $stacksSelected[] = $stacksEntry[\'stID\'];
            }
            foreach ($stacksSelected as $key) {
                if (array_key_exists($key, $stacksOptions)) {
                    $ordered[$key] = $stacksOptions[$key];
                    unset($stacksOptions[$key]);
                }
            }
            $stacksOptions = $ordered + $stacksOptions;
        }
        $this->set(\'' . $this->data['slug'] . '_options\', $stacksOptions);
        $this->set(\'' . $this->data['slug'] . '_selected\', $stacksSelected);';
        return implode(PHP_EOL . '        ', $lines);
    }

    public function getExtraFunctionsContents()
    {
        if ($this->data['ft_count'] <= 0) {
            return 'private function getStacks()
    {
        $stacksOptions = array();
        $stm = new StackList();
        $stm->filterByUserAdded();
        $stacks = $stm->get();
        foreach ($stacks as $st) {
            $sv = CollectionVersion::get($st, \'ACTIVE\');
            $stacksOptions[$st->getCollectionID()] = $sv->getVersionName();
        }
        return $stacksOptions;
    }';
        }
    }

    public function getDeleteFunctionContents()
    {
        return '$db->delete(\'' . $this->getEntriesTableName($this->data) . '\', array(\'bID\' => $this->bID));';
    }

    public function getDuplicateFunctionContents()
    {
        return '$stacksEntries = $db->GetAll(\'SELECT * from ' . $this->getEntriesTableName($this->data) . ' WHERE bID = ? ORDER BY sortOrder ASC\', array($this->bID));
        foreach ($stacksEntries as $stacksEntry) {
            unset($stacksEntry[\'id\']);
            $db->insert(\'' . $this->getEntriesTableName($this->data) . '\', $stacksEntry);
        }';
    }

    public function getSaveFunctionContents()
    {
        $entriesTableName = $this->getEntriesTableName($this->data);
        return '$stacksEntriesDB = array();
        $queries = array();
        if ($stacksEntries = $db->GetAll(\'SELECT * FROM ' . $entriesTableName . ' WHERE bID = ? ORDER BY sortOrder ASC\', array($this->bID))) {
            foreach ($stacksEntries as $stacksEntry) {
                $stacksEntriesDB[] = $stacksEntry[\'id\'];
            }
        }
        if (isset($args[\'' . $this->data['slug'] . '\']) && is_array($args[\'' . $this->data['slug'] . '\'])) {
            $stacksOptions = $this->getStacks();
            $i = 0;
            foreach ($args[\'' . $this->data['slug'] . '\'] as $stackID) {
                if ($stackID > 0 && array_key_exists($stackID, $stacksOptions)) {
                    $this->data = array(
                        \'stID\'      => $stackID,
                        \'sortOrder\' => $i,
                    );
                    if (!empty($stacksEntriesDB)) {
                        $stackEntryKey = key($stacksEntriesDB);
                        $stackEntryValue = $stacksEntriesDB[$stackEntryKey];
                        $queries[\'update\'][$stackEntryValue] = $this->data;
                        unset($stacksEntriesDB[$stackEntryKey]);
                    } else {
                        $this->data[\'bID\'] = $this->bID;
                        $queries[\'insert\'][] = $this->data;
                    }
                    $i++;
                }
            }
        }
        if (!empty($stacksEntriesDB)) {
            foreach ($stacksEntriesDB as $stacksEntryDB) {
                $queries[\'delete\'][] = $stacksEntryDB;
            }
        }
        if (!empty($queries)) {
            foreach ($queries as $type => $values) {
                if (!empty($values)) {
                    switch ($type) {
                        case \'update\':
                            foreach ($values as $id => $this->data) {
                                $db->update(\'' . $entriesTableName . '\', $this->data, array(\'id\' => $id));
                            }
                            break;
                        case \'insert\':
                            foreach ($values as $this->data) {
                                $db->insert(\'' . $entriesTableName . '\', $this->data);
                            }
                            break;
                        case \'delete\':
                            foreach ($values as $value) {
                                $db->delete(\'' . $entriesTableName . '\', array(\'id\' => $value));
                            }
                            break;
                    }
                }
            }
        }';
    }

    public function getValidateFunctionContents()
    {
        return 'if (in_array("' . $this->data['slug'] . '", $this->btFieldsRequired) && (!isset($args[\'' . $this->data['slug'] . '\']) || (!is_array($args[\'' . $this->data['slug'] . '\']) || empty($args[\'' . $this->data['slug'] . '\'])))) {
            $e->add(t("The %s field is required.", t("' . htmlspecialchars($this->data['label']) . '")));
        }
        else {
            $stacksPosted = 0;
            $stacksMin = ' . (isset($this->data['min_length']) && $this->data['min_length'] >= 1 ? $this->data['min_length'] : 'null') . ';
            $stacksMax = ' . (isset($this->data['max_length']) && $this->data['max_length'] >= 1 ? $this->data['max_length'] : 'null') . ';
            if(isset($args[\'' . $this->data['slug'] . '\']) && is_array($args[\'' . $this->data['slug'] . '\'])){
                $args[\'' . $this->data['slug'] . '\'] = array_unique($args[\'' . $this->data['slug'] . '\']);
                foreach($args[\'' . $this->data['slug'] . '\'] as $stID){
                    if($st = Stack::getByID($stID)){
                        $stacksPosted++;
                    }
                }
            }
            if($stacksMin != null && $stacksMin >= 1 && $stacksPosted < $stacksMin){
                $e->add(t("The %s field needs a minimum of %s stacks.", t("' . htmlspecialchars($this->data['label']) . '"), $stacksMin));
            }
            elseif($stacksMax != null && $stacksMax >= 1 && $stacksMax > $stacksMin && $stacksPosted > $stacksMax){
                $e->add(t("The %s field has a maximum of %s stacks.", t("' . htmlspecialchars($this->data['label']) . '"), $stacksMax));
            }
        }';
    }

    public function getFormContents()
    {
        $code = null;
        if ($this->data['ft_count'] <= 0) {
            $code .= '<style>
        .select2-container.form-control {
            border: 1px solid #ccc;
        }
    </style>';
        }
        return '<div class="form-group">
    ' . parent::generateFormContent('label', array('slug' => $this->data['slug'], 'label' => $this->data['label'])) . '
    ' . parent::generateFormContent('required', array('slug' => $this->data['slug'], 'array' => '$btFieldsRequired')) . '
    ' . parent::generateFormContent('select_multiple', array('slug' => $this->data['slug'], 'options' => '$' . $this->data['slug'] . '_options', 'defaultValues' => 'isset($' . $this->data['slug'] . '_selected) ? $' . $this->data['slug'] . '_selected : array()')) . '
    <script>
        $(document).ready(function () {
            $(\'select[name="' . $this->data['slug'] . '[]"]\').select2_sortable();
        });
    </script>
</div>';
    }

    public function getOnStartFunctionContents()
    {
        if ($this->data['ft_count'] <= 0) {
            return '$al->register(\'javascript\', \'select2sortable\', \'blocks/' . $this->data['block_handle'] . '/js_form/select2.sortable.js\');';
        }
    }

    public function getDbTables()
    {
        return array(
            $this->getEntriesTableName($this->data) => array(
                'fields' => array(
                    array(
                        'name'       => 'id',
                        'type'       => 'I',
                        'attributes' => array(
                            'key'           => true,
                            'unsigned'      => true,
                            'autoincrement' => true,
                        )
                    ),
                    array(
                        'name'       => 'bID',
                        'type'       => 'I',
                        'attributes' => array(
                            'unsigned' => true,
                        )
                    ),
                    array(
                        'name'       => 'stID',
                        'type'       => 'I',
                        'attributes' => array(
                            'unsigned' => true,
                        )
                    ),
                    array(
                        'name' => 'sortOrder',
                        'type' => 'I',
                    ),
                )
            )
        );
    }

    public function getFieldOptions()
    {
        return parent::view('field_options.php');
    }
}