<?php  namespace Concrete\Package\BlockDesigner\Src;

defined('C5_EXECUTE') or die(_("Access Denied."));

use Package;
use Loader;
use Config;
use Concrete\Core\File\Service\File;
use Concrete\Core\File\Service;
use Concrete\Flysystem\Adapter\Local;

class BlockDesignerProcessor
{
    public static $packageHandle = 'block_designer';

    private static $errors = array();

    public function getPackageFolder($pkgHandle)
    {
        return 'packages' . DIRECTORY_SEPARATOR . $pkgHandle . DIRECTORY_SEPARATOR;
    }

    public static function getBlocksFolder()
    {
        return 'application' . DIRECTORY_SEPARATOR . 'blocks';
    }

    public static function getBlockTypeFolder($btHandle = null)
    {
        return self::getBlocksFolder() . DIRECTORY_SEPARATOR . $btHandle;
    }

    public static function getFieldTypes()
    {
        $results = array();
        $pkgBd = Package::getByHandle(self::$packageHandle);
        $pkgBdVersion = $pkgBd->getPackageVersion();
        $packages = array(self::$packageHandle, 'block_designer_pro');
        foreach ($packages as $packageHandle) {
            if (($pkg = Package::getByHandle($packageHandle)) && $pkg->isPackageInstalled() == '1') {
                $pkgDirectory = self::getPackageFolder($packageHandle);
                $directory = $pkgDirectory . 'src' . DIRECTORY_SEPARATOR . 'FieldType';
                $folders = self::getDirList($directory);
                if (!empty($folders)) {
                    foreach ($folders as $folder) {
                        $ftDirectory = $directory . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR;
                        $ftClassName = self::_nameSpace($folder);
                        $ftNamespace = 'Concrete\Package\\' . self::_namespace($packageHandle) . '\Src\FieldType\\' . $ftClassName . '\\' . $ftClassName;
                        /* @var $ftClass \Concrete\Package\BlockDesigner\Src\FieldType\FieldType */
                        $ftClass = new $ftNamespace($ftDirectory, $packageHandle, $pkgDirectory, $ftClassName);
                        $pkgVersionRequired = $ftClass->getPkgVersionRequired();
                        $appVersionRequired = $ftClass->getAppVersionRequired();
                        if (($pkgVersionRequired === false || version_compare($pkgVersionRequired, $pkgBdVersion, '<=')) && ($appVersionRequired === false || version_compare($appVersionRequired, Config::get('concrete.version_installed'), '<='))) {
                            $iconFile = $ftDirectory . 'icon.png';
                            $icon = file_exists($iconFile) ? $iconFile : self::getPackageFolder($packageHandle) . 'img' . DIRECTORY_SEPARATOR . 'icon.png';
                            $results[$ftClass->getHandle()] = array(
                                'icon'         => DIR_REL . DIRECTORY_SEPARATOR . $icon,
                                'name'         => $ftClass->getFieldName(),
                                'description'  => $ftClass->getFieldDescription(),
                                'namespace'    => $ftNamespace,
                                'directory'    => $ftDirectory,
                                'pkgHandle'    => $packageHandle,
                                'pkgDirectory' => $pkgDirectory,
                                'class'        => $ftClass,
                                'className'    => $ftClassName,
                            );
                        }
                    }
                }
            }
        }
        return $results;
    }

    private static function _nameSpace($name)
    {
        $nameSpaced = implode('', array_map(function ($v, $k) {
            return ucFirst($v);
        }, explode('_', $name), array_keys(explode('_', $name))));
        return $nameSpaced;
    }

    private static function getDirList($d)
    {
        foreach (array_diff(scandir($d), array('.', '..')) as $f) if (is_dir($d . '/' . $f)) $l[] = $f;
        return $l;
    }

    public static function runErrors()
    {
        return self::$errors;
    }

    public static function blockHandle($blockHandle = null)
    {
        $blockHandle = strtolower(str_replace('-', '_', $blockHandle));
        return preg_replace('~_+~', '_', $blockHandle); // replacing multiple underscores with just 1
    }

    public static function run($postData = array())
    {
        $autoJs = null;
        $extraFunction = array();
        $viewFields = array();
        $formFields = array();
        $dbTables = array();
        $fieldSlugs = array();
        $copyFiles = array();
        $controllerVariablesArray = array();
        $fieldSlugsBlacklist = array('limit', 'description', 'select', 'file', 'bid', 'bttable', 'helpers', 'btfieldsrequired', 'btinterfacewidth', 'btinterfaceheight', 'btcacheblockrecord', 'btcacheblockoutput', 'btcacheblockoutputonpost', 'btcacheblockoutputforregisteredusers', 'btcacheblockoutputlifetime', 'bthandle', 'btname', 'btexportpagecolumns', 'btexportfilecolumns', 'btexportpagetypecolumns', 'btexportpagefeedcolumns', 'btwrapperclass');
        $dbFields = array(
            array(
                'name'       => 'bID',
                'type'       => 'I',
                'attributes' => array(
                    'key'      => true,
                    'unsigned' => true,
                )
            ),
        );
        $fieldTypes = self::getFieldTypes();
        $blockHandle = self::blockHandle($postData['block_handle']);
        $blockHandleNamespaced = self::_nameSpace($blockHandle);
        $blockTypeFolder = self::getBlockTypeFolder($postData['block_handle']);
        $btTable = 'bt' . (isset($postData['table_prefix']) && trim($postData['table_prefix']) != '' ? $postData['table_prefix'] : null) . $blockHandleNamespaced;
        $controllerUsesArray = array('Concrete\Core\Block\BlockController', 'Core', 'Loader');
        $controllerVariables = array(
            'helpers'                              => array(
                'type'  => 'public',
                'value' => array('form'),
            ),
            'btFieldsRequired'                     => array(
                'type'  => 'public',
                'value' => array(),
            ),
            'btExportFileColumns'                  => array(
                'type'  => 'protected',
                'value' => array(),
            ),
            'btExportTables'                       => array(
                'type'  => 'protected',
                'value' => array($btTable),
            ),
            'btTable'                              => array(
                'type'  => 'protected',
                'value' => $btTable,
            ),
            'btInterfaceWidth'                     => array(
                'type'  => 'protected',
                'value' => (int)$postData['interface_width'],
            ),
            'btInterfaceHeight'                    => array(
                'type'  => 'protected',
                'value' => (int)$postData['interface_height'],
            ),
            'btCacheBlockRecord'                   => array(
                'type'  => 'protected',
                'value' => isset($postData['cache_block_record']) && $postData['cache_block_record'] == 0 ? false : true,
            ),
            'btCacheBlockOutput'                   => array(
                'type'  => 'protected',
                'value' => isset($postData['cache_block_output']) && $postData['cache_block_output'] == 0 ? false : true,
            ),
            'btCacheBlockOutputOnPost'             => array(
                'type'  => 'protected',
                'value' => isset($postData['cache_block_output_on_post']) && $postData['cache_block_output_on_post'] == 0 ? false : true,
            ),
            'btCacheBlockOutputForRegisteredUsers' => array(
                'type'  => 'protected',
                'value' => isset($postData['cache_block_output_for_registered_users']) && $postData['cache_block_output_for_registered_users'] == 0 ? false : true,
            ),
        );
        $controllerFunctionsArray = array();
        $controllerFunctions = array(
            'getBlockTypeDescription' => array(
                'type'  => 'public',
                'lines' => array(
                    'return t("' . htmlspecialchars($postData['block_description']) . '");',
                ),
            ),
            'getBlockTypeName'        => array(
                'type'  => 'public',
                'lines' => array(
                    'return t("' . htmlspecialchars($postData['block_name']) . '");',
                ),
            ),
            'getSearchableContent'    => array(
                'type'  => 'public',
                'lines' => array(),
            ),
            'on_start'                => array(
                'type'  => 'public',
                'lines' => array(),
            ),
            'view'                    => array(
                'type'  => 'public',
                'lines' => array(),
            ),
            'delete'                  => array(
                'type'  => 'public',
                'lines' => array(),
            ),
            'duplicate'               => array(
                'type'      => 'public',
                'lines'     => array(),
                'variables' => array('$newBID'),
            ),
            'add'                     => array(
                'type'  => 'public',
                'lines' => array(),
            ),
            'edit'                    => array(
                'type'  => 'public',
                'lines' => array(),
            ),
            'save'                    => array(
                'type'      => 'public',
                'lines'     => array(),
                'variables' => array('$args'),
            ),
            'validate'                => array(
                'type'      => 'public',
                'lines'     => array(),
                'variables' => array('$args'),
            ),
            'composer'                => array(
                'type'      => 'public',
                'lines'     => array('$this->edit();'),
            ),
        );
        if (isset($postData['cache_block_output_lifetime']) && $postData['cache_block_output_lifetime'] >= 0) {
            $controllerVariables['btCacheBlockOutputLifetime'] = array(
                'type'  => 'protected',
                'value' => (int)$postData['cache_block_output_lifetime'],
            );
        }
        foreach ($postData['fields'] as $key => $value) {
            if (isset($value['type'])) {
                if (array_key_exists($value['type'], $fieldTypes)) {
                    $fieldType = $fieldTypes[$value['type']];
                    /* @var $fieldTypeClass \Concrete\Package\BlockDesigner\Src\FieldType\FieldType */
                    $fieldTypeClass = new $fieldType['namespace']($fieldType['directory'], $fieldType['pkgHandle'], $fieldType['pkgDirectory'], $fieldType['className']);
                    if ($fieldTypeClass->getRequiredSlug() === true) {
                        if (isset($value['slug']) && trim($value['slug']) != '') {
                            // Being sure we have a non-existing slug for the field
                            $slug_num = 1;
                            $slug = $value['slug'];
                            while (in_array($slug, $fieldSlugs) || in_array(strtolower($slug), $fieldSlugsBlacklist)) {
                                $slug = $value['slug'] . '_' . $slug_num;
                                $slug_num++;
                            }
                            $fieldSlugs[] = $slug;
                        } else {
                            self::$errors[] = t('There was no slug found for row #%s. Please try again.', $key);
                            break;
                        }
                    } else {
                        $slug = false;
                    }
                    $required = isset($value['required']) && is_string($value['required']) && $value['required'] == '1' ? true : false;
                    if ($required && $slug) {
                        $controllerVariables['btFieldsRequired']['value'][] = $slug;
                    }
                    $prefix = PHP_EOL . '    ' . (isset($value['prefix']) && trim($value['prefix']) != '' ? trim($value['prefix']) : null);
                    $suffix = (isset($value['suffix']) && trim($value['suffix']) != '' ? trim($value['suffix']) : null);
                    if (!isset($fieldTypeCounts[$value['type']])) {
                        $fieldTypeCounts[$value['type']] = 0;
                    }
                    $fieldData = array_merge($value, array(
                            'row_id'       => $key,
                            'slug'         => $slug,
                            'required'     => $required,
                            'prefix'       => $prefix,
                            'suffix'       => $suffix,
                            'label'        => $value['label'],
                            'ft_count'     => $fieldTypeCounts[$value['type']],
                            'btDirectory'  => $blockTypeFolder . DIRECTORY_SEPARATOR,
                            'btTable'      => $btTable,
                            'block_handle' => $blockHandle,
                        )
                    );
                    $fieldTypeClass->setData($fieldData);
                    $continue = true;
                    if (method_exists($fieldTypeClass, 'validate') && ($validateResult = $fieldTypeClass->validate()) !== true) {
                        self::$errors[] = $validateResult;
                        $continue = false;
                    }
                    if ($continue) {
                        if (isset($value['repeatable']) && trim($value['repeatable']) != '' && $fieldTypeClass->getCanRepeat() === true && isset($postData['fields'][$value['repeatable']]) && array_key_exists('repeatable', $fieldTypes)) {
                            // @todo
                        } else {
                            if (method_exists($fieldTypeClass, 'getBtExportTables')) {
                                if (($result = $fieldTypeClass->getBtExportTables()) && is_array($result)) {
                                    $controllerVariables['btExportTables']['value'] = array_merge($controllerVariables['btExportTables']['value'], $result);
                                }
                            }
                            $classMethods = array(
                                array(
                                    'method'   => 'getViewContents',
                                    'variable' => 'viewFields',
                                ),
                                array(
                                    'method'   => 'getFormContents',
                                    'variable' => 'formFields',
                                ),
                                array(
                                    'method'   => 'getExtraFunctionsContents',
                                    'variable' => 'extraFunction',
                                ),
                            );
                            foreach ($classMethods as $classMethod) {
                                if (method_exists($fieldTypeClass, $classMethod['method'])) {
                                    if ($result = $fieldTypeClass->{$classMethod['method']}()) {
                                        ${$classMethod['variable']}[] = $result;
                                    }
                                }
                            }
                            $classMethods = array(
                                array(
                                    'method'   => 'getOnStartFunctionContents',
                                    'variable' => 'on_start',
                                ),
                                array(
                                    'method'   => 'getValidateFunctionContents',
                                    'variable' => 'validate',
                                ),
                                array(
                                    'method'   => 'getEditFunctionContents',
                                    'variable' => 'edit',
                                ),
                                array(
                                    'method'   => 'getDeleteFunctionContents',
                                    'variable' => 'delete',
                                ),
                                array(
                                    'method'   => 'getDuplicateFunctionContents',
                                    'variable' => 'duplicate',
                                ),
                                array(
                                    'method'   => 'getAddFunctionContents',
                                    'variable' => 'add',
                                ),
                                array(
                                    'method'   => 'getViewFunctionContents',
                                    'variable' => 'view',
                                ),
                                array(
                                    'method'   => 'getSaveFunctionContents',
                                    'variable' => 'save',
                                ),
                                array(
                                    'method'   => 'getSearchableContent',
                                    'variable' => 'getSearchableContent',
                                ),
                            );
                            foreach ($classMethods as $classMethod) {
                                if (method_exists($fieldTypeClass, $classMethod['method'])) {
                                    if ($result = $fieldTypeClass->{$classMethod['method']}()) {
                                        if (!isset($controllerFunctions[$classMethod['variable']])) {
                                            $controllerFunctions[$classMethod['variable']] = array(
                                                'type'  => 'public',
                                                'lines' => array(),
                                            );
                                        }
                                        $controllerFunctions[$classMethod['variable']]['lines'][] = $result;
                                    }
                                }
                            }
                            if (method_exists($fieldTypeClass, 'getAutoJsContents')) {
                                if (($result = $fieldTypeClass->getAutoJsContents()) && trim($result) != '') {
                                    $autoJs .= $result;
                                }
                            }
                            if (method_exists($fieldTypeClass, 'getDbFields')) {
                                if (($result = $fieldTypeClass->getDbFields()) && is_array($result) && !empty($result)) {
                                    $dbFields = array_merge($dbFields, $result);
                                }
                            }
                            if (method_exists($fieldTypeClass, 'getDbTables')) {
                                if (($result = $fieldTypeClass->getDbTables()) && is_array($result) && !empty($result)) {
                                    $dbTables = array_merge($dbTables, $result);
                                }
                            }
                            if ($slug && $fieldTypeClass->getBtExportFileColumn() === true) {
                                $controllerVariables['btExportFileColumns']['value'][] = $slug;
                            }
                        }
                        if (method_exists($fieldTypeClass, 'copyFiles')) {
                            if (($result = $fieldTypeClass->copyFiles()) && is_array($result) && !empty($result)) {
                                $copyFiles = array_merge($copyFiles, $result);
                            }
                        }
                        if (($helpers = $fieldTypeClass->getHelpers()) && is_array($helpers) && !empty($helpers)) {
                            foreach ($helpers as $helper) {
                                if (!in_array($helper, $controllerVariables['helpers']['value'])) {
                                    $controllerVariables['helpers']['value'][] = $helper;
                                }
                            }
                        }
                        if (($uses = $fieldTypeClass->getUses()) && is_array($uses) && !empty($uses)) {
                            foreach ($uses as $use) {
                                if (!in_array($use, $controllerUsesArray)) {
                                    $controllerUsesArray[] = $use;
                                }
                            }
                        }
                        $fieldTypeCounts[$value['type']]++;
                    }
                } else {
                    self:: $errors[] = t("An unknown field type was posted. Please try again.");
                    break;
                }
            } else {
                unset($postData['fields'][$key]);
            }
        }
        if (!empty(self::$errors)) {
            return false;
        } else {
            // Some final lines for our functions
            if (!empty($controllerFunctions['on_start']['lines'])) {
                array_unshift($controllerFunctions['on_start']['lines'], '$al = \Concrete\Core\Asset\AssetList::getInstance();');
            }
            if (!empty($controllerFunctions['view']['lines'])) {
                array_unshift($controllerFunctions['view']['lines'], '$db = \Database::get();');
            }
            if (!empty($controllerFunctions['edit']['lines'])) {
                array_unshift($controllerFunctions['edit']['lines'], '$db = \Database::get();');
            }
            if (!empty($controllerFunctions['delete']['lines'])) {
                array_unshift($controllerFunctions['delete']['lines'], '$db = \Database::get();');
                $controllerFunctions['delete']['lines'][] = 'parent::delete();';
            }
            if (!empty($controllerFunctions['duplicate']['lines'])) {
                array_unshift($controllerFunctions['duplicate']['lines'], '$db = \Database::get();');
                $controllerFunctions['duplicate']['lines'][] = 'parent::duplicate($newBID);';
            }
            if (!empty($controllerFunctions['save']['lines'])) {
                array_unshift($controllerFunctions['save']['lines'], '$db = \Database::get();');
                $controllerFunctions['save']['lines'][] = 'parent::save($args);';
            }
            if (!empty($controllerFunctions['validate']['lines'])) {
                array_unshift($controllerFunctions['validate']['lines'], '$e = Core::make("helper/validation/error");');
                $controllerFunctions['validate']['lines'][] = 'return $e;';
            }
            if (!empty($controllerFunctions['getSearchableContent']['lines'])) {
                array_unshift($controllerFunctions['getSearchableContent']['lines'], '$content = array();');
                $controllerFunctions['getSearchableContent']['lines'][] = 'return implode(" ", $content);';
            }
            $controllerFunctions['add']['lines'][] = '$this->set(\'btFieldsRequired\', $this->btFieldsRequired);';
            $controllerFunctions['edit']['lines'][] = '$this->set(\'btFieldsRequired\', $this->btFieldsRequired);';
            foreach ($controllerFunctions as $key => $controllerFunction) {
                if (isset($controllerFunction['lines']) && !empty($controllerFunction['lines'])) {
                    $controllerFunctionsArray[] = $controllerFunction['type'] . ' function ' . $key . '(' . (isset($controllerFunction['variables']) && is_array($controllerFunction['variables']) && !empty($controllerFunction['variables']) ? implode(', ', $controllerFunction['variables']) : null) . ')
    {
        ' . implode(PHP_EOL . self::getTabs(2), $controllerFunction['lines']) . '
    }
';
                }

            }
            // Copy block/field type related files
            $fileService = new File();
            $fileService->copyAll(self::getPackageFolder(self::$packageHandle) . 'template', $blockTypeFolder);
            foreach ($copyFiles as $copy_file) {
                if (is_array($copy_file) && isset($copy_file['source'], $copy_file['target'])) {
                    $fileService->copyAll($copy_file['source'], $copy_file['target'], isset($copy_file['mode']) ? $copy_file['mode'] : null);
                }
            }
            $dbTables = array_merge(array($btTable => array('fields' => $dbFields)), $dbTables);
            if (count($controllerVariables['btExportTables']['value']) <= 1) {
                unset($controllerVariables['btExportTables']);
            } else {
                $controllerVariables['btExportTables']['value'] = array_unique($controllerVariables['btExportTables']['value']);
            }
            foreach ($controllerVariables as $key => $class_variable) {
                $controllerVariablesArray[] = $class_variable['type'] . ' $' . $key . ' = ' . var_export($class_variable['value'], true) . ';';
            }
            $controllerUsesString = implode('', array_map(function ($v, $k) {
                return sprintf('use %s;' . PHP_EOL, $v);
            }, $controllerUsesArray, array_keys($controllerUsesArray)));
            // Get a block image, if posted along
            if ($block_image = isset($_FILES, $_FILES["block_image"]) ? $_FILES["block_image"] : false) {
                $allowedExts = array("png");
                $temp = explode(".", $block_image["name"]);
                $extension = end($temp);
                if (in_array($extension, $allowedExts) && $block_image["error"] <= 0) {
                    move_uploaded_file($block_image["tmp_name"], $blockTypeFolder . DIRECTORY_SEPARATOR . 'icon.png');
                }
            }
            $files = array(
                array(
                    'name'         => $blockTypeFolder . DIRECTORY_SEPARATOR . 'auto.js',
                    'text'         => trim($autoJs) != '' ? $autoJs : null,
                    'delete_empty' => true,
                ),
                array(
                    'name'         => $blockTypeFolder . DIRECTORY_SEPARATOR . 'view.js',
                    'text'         => isset($postData['view_js']) && is_string($postData['view_js']) && trim($postData['view_js']) != '' ? $postData['view_js'] : null,
                    'delete_empty' => true,
                ),
                array(
                    'name'         => $blockTypeFolder . DIRECTORY_SEPARATOR . 'view.css',
                    'text'         => isset($postData['view_css']) && is_string($postData['view_css']) && trim($postData['view_css']) != '' ? $postData['view_css'] : null,
                    'delete_empty' => true,
                ),
                array(
                    'name' => $blockTypeFolder . DIRECTORY_SEPARATOR . 'config.json',
                    'text' => json_encode($postData),
                ),
                array(
                    'name' => $blockTypeFolder . DIRECTORY_SEPARATOR . 'view.php',
                    'text' => PHP_EOL . PHP_EOL . implode(PHP_EOL, $viewFields),
                ),
                array(
                    'name' => $blockTypeFolder . DIRECTORY_SEPARATOR . 'db.xml',
                    'text' => self::buildSchema($dbTables),
                ),
                array(
                    'name' => $blockTypeFolder . DIRECTORY_SEPARATOR . 'form.php',
                    'text' => PHP_EOL . PHP_EOL . implode(PHP_EOL . PHP_EOL, $formFields)
                ),
                array(
                    'clear' => true,
                    'name'  => $blockTypeFolder . DIRECTORY_SEPARATOR . 'controller.php',
                    'text'  => '<?php  namespace Application\Block\\' . $blockHandleNamespaced . ';

defined("C5_EXECUTE") or die("Access Denied.");' . (trim($controllerUsesString) != '' ? PHP_EOL . PHP_EOL . $controllerUsesString : null) . '
class Controller extends BlockController
{
' . self::getTabs(1) . (!empty($controllerVariablesArray) ? implode(PHP_EOL . self::getTabs(1), $controllerVariablesArray) : null) . '
' . self::getTabs(1) . (!empty($controllerFunctionsArray) ? PHP_EOL . self::getTabs(1) . implode(PHP_EOL . self::getTabs(1), $controllerFunctionsArray) : null) . '
' . self::getTabs(1) . (!empty($extraFunction) ? PHP_EOL . self::getTabs(1) . implode(PHP_EOL . PHP_EOL, $extraFunction) : null) . '
}'
                ),
            );
            foreach ($files as $file) {
                if (isset($file['delete_empty']) && $file['delete_empty'] === true && trim($file['text']) == '') {
                    @unlink($file['name']);
                } else {
                    if (isset($file['clear']) && $file['clear'] === true) {
                        $fileService->clear($file['name']);
                    }
                    $fileService->append($file['name'], $file['text']);
                }
            }
            return true;
        }
    }

    public function buildSchema($tables = array())
    {
        $html = '<?php xml version="1.0"?>' . PHP_EOL . '<schema version="0.3">';
        foreach ($tables as $k => $v) {
            $fields = array();
            if (isset($v['fields']) && is_array($v['fields']) && !empty($v['fields'])) {
                foreach ($v['fields'] as $field) {
                    if (is_array($field) && isset($field['name'], $field['type'])) {
                        $inside = array();
                        if (isset($field['attributes']) && is_array($field['attributes'])) {
                            foreach ($field['attributes'] as $attributeKey => $attributeValue) {
                                switch (strtoupper($attributeKey)) {
                                    case 'DEFDATE':
                                    case 'DEFTIMESTAMP':
                                    case 'NOQUOTE':
                                    case 'CONSTRAINTS':
                                        // Not sure what the above ones do
                                    case 'AUTOINCREMENT':
                                    case 'KEY':
                                    case 'PRIMARY':
                                        if ((bool)$attributeValue === true) {
                                            $inside[] = '<' . strtolower($attributeKey) . '/>';
                                        }
                                        break;
                                    case 'DEF':
                                    case 'DEFAULT':
                                        $inside[] = '<default value="' . $attributeValue . '"/>';
                                        break;
                                    case 'UNSIGNED':
                                    case 'NOTNULL':
                                        if ((bool)$attributeValue === true) {
                                            $inside[] = '<unsigned/>';
                                        }
                                        break;
                                    default:
                                        break;
                                }
                            }
                        }
                        $fields[] = '<field name="' . $field['name'] . '" type="' . $field['type'] . '"' . (isset($field['size']) && trim($field['size']) != '' ? ' size="' . $field['size'] . '"' : null) . '>' . (!empty($inside) ? PHP_EOL . self::getTabs(3) . implode(PHP_EOL . self::getTabs(3), $inside) . PHP_EOL . self::getTabs(2) : null) . '</field>';
                    }
                }
            }
            if (!empty($fields)) {
                $html .= PHP_EOL . self::getTabs(1) . '<table name="' . $k . '">' . PHP_EOL . self::getTabs(2) . implode(PHP_EOL . self::getTabs(2), $fields) . PHP_EOL . self::getTabs(1) . '</table>';
            }
        }
        $html .= PHP_EOL . '</schema>';
        return $html;
    }

    private function getTabs($count = 1)
    {
        $return = '';
        if ($count >= 1) {
            for ($i = 1; $i <= $count; $i++) {
                $return .= '    ';
            }
        }
        return $return;
    }
}