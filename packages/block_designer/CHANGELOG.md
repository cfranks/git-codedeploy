#v1.2.0

* Static HTML field description updated, to let users know they can paste in other scripting languages (CSS/JS/PHP) too;
* Upon clicking "Make the block!", the ajax post will not send the block handle in the URL instead of in the data object - this due to the fact when SEO "trailing_slash" was set on true, it was not being posted (possible C5 bug);
* Fixed issue where wrapper HTML open/close wouldn't show for the file & stacks field types;
* Added ability to hide title field for the "Link" field type - this means the page title/name is always being shown and no alternate title field is available;
* Added ability "New line to blank rule"/nl2br for the textarea field type;
* Updated "Parsedown" library in markdown field type from version 1.5.1 to 1.5.3;
* Updated stacks field type to only write select2 (sortable) CSS once - if multiple stack field types are being used in a block;
* Field types now use the field function "$view->field('fieldName')" in their form.php file, instead of a normal string i.e. 'fieldName';
* Developers: Complete field types rewrite from classes to Namespaces;
* Developers: Renamed field type function "viewContents" to "getViewContents";
* Developers: Renamed field type function "viewFunctionContents" to "getViewFunctionContents";
* Developers: Renamed field type function "formContents" to "getFormContents";
* Developers: Renamed field type function "extraFunctionsContents" to "getExtraFunctionsContents";
* Developers: Renamed field type function "on_startFunctionContents" to "getOnStartFunctionContents";
* Developers: Renamed field type function "validateFunctionContents" to "getValidateFunctionContents";
* Developers: Renamed field type function "editFunctionContents" to "getEditFunctionContents";
* Developers: Renamed field type function "deleteFunctionContents" to "getDeleteFunctionContents";
* Developers: Renamed field type function "duplicateFunctionContents" to "getDuplicateFunctionContents";
* Developers: Renamed field type function "addFunctionContents" to "getAddFunctionContents";
* Developers: Renamed field type function "saveFunctionContents" to "getSaveFunctionContents";
* Developers: Renamed field type function "autoJsContents" to "getAutoJsContents";
* Developers: Renamed field type function "dbFields" to "getDbFields";
* Developers: Renamed field type function "dbTables" to "getDbTables";
* Developers: Added function "getBtExportTables" for field types, which can return an array of tables (to be used with the Concrete5 export);
* Developers: Added function "getExtraOptions" for field types, which can return extra HTML (usage of Handlebars.js is available) for each and every added field;
* Developers: Updated "getDbTables" (previously named "dbTables" function) functionality in field types;
* Developers: Triggering event "remove" upon removing/deleting a field (.content-field);
* Developers: Triggering event "create" upon creating/adding a field (.content-field);
* Developers: Triggering event "complete" upon filling all fields (.content-fields);
* Developers: Field type "link" rewrite within "getViewContents" function -> Loader::helper("navigation")->getLinkToCollection($linkToC) to $linkToC->getCollectionLink();
* Developers: Field type "link" rewrite within "getFormContents" function -> Loader::helper("form/page_selector") to Core::make("helper/form/page_selector");
* Developers: Moved "$this->requireAsset()" for field type "stacks" to the add/edit functions instead of on_start;
* Developers: Moved field type field_options.php's files to "views" folder (i.e. view/FieldType/ColorPickerFieldType/field_options.php);
* Developers: Field types' variables are now protected instead of public;
* Developers: Field types can now require a specific Concrete5 version because of dependencies (usage: protected $appVersionRequired = '5.7.3.1'). If the version does not match, the field type won't be available until the required Concrete5 version is installed;

#v1.1.1

* Added a set of blacklisted block handles (mostly words which have special meaning in PHP, see: http://php.net/manual/en/reserved.keywords.php);
* Removed 4 pixels border (bottom) on the responsive tabs - this was causing inconsistent padding on the tabs;
* Fixed if entered JavaScript in the "View JavaScript" field, that Block Designer will write to view.js instead of auto.js;
* Developers: Field types can now return JavaScript being written to the "auto.js" file;
* Developers: Field types can now require a specific Block Designer version because of dependencies (usage: public $pkgVersionRequired = '1.1.1'). If the version does not match, the field type won't be available until the required version is installed;

#v1.1.0

* Extra blacklisted slug "description" - using this slug for a field would cause SQL issues;
* Added ability to enter rows of colors (unlimited) for the color picker's palette - this slides down when checking the checkbox "Show a palette";
* Fixed select field type's keys being rendered as plain text if keys were set using "concrete5 :: Concrete5 CMS 5.7";
* Fixed select field type's key being rendered as original key number minus 1 (-1), when a select field was not set on "required";
* Fixed Block Configs not showing up on the "dashboard/blocks/block_designer/block_config" page - only happened with identical block type names;
* Developers: Added ability to auto load field_css.css within the field types' "elements" directory (if exists);
* Developers: Removed color picker's JavaScript indenting;
* Developers: Updated Handlebars.js to the latest available version - v3.0.3;

#v1.0.11

* Added a "View CSS" field under the "Advanced" tab. This will allow you to input some CSS to be copied into a view.css file;
* Added a "View Javascript" field under the "Advanced" tab. This will allow you to input some Javascript to be copied into a auto.js file;
* Added ability to have an URL Link on the "image" field type, next to the existing Page Link;

#v1.0.10

* Replaced jquery.responsiveTabs.min.js with a non minified version - 5.7.4 was causing issues for some strange reason;

#v1.0.9

* Brought back bootstrap fonts (bootstrap.fonts.css and "fonts" directory), as there is no Concrete5 asset (yet);

#v1.0.8

* Field type icon fix for Concrete5 5.7.4.x and higher (based on the release candidate of 5.7.4);

#v1.0.7

* Better field type sorting in the "Add a field" section - only visible when names of field types differ from directory name or when pulled from more directories (read: other package(s));
* Cleaned HTML if class for field type "Youtube" was empty (a superfluous empty attribute "class" would be attached to the iFrame);
* Removed 1 (of 2) extra "End of Line" being generated after each field, resulting in a more compact view.php file;
* Developers: registered new handlebarsjs helper "select_multiple" - this for select input fields with the "multiple" attribute;
* Developers: Removed "fieldOptionsJavascript" function for field types and instead autoloads field_javascript.js within the field types' "elements" directory (if exists);

#v1.0.6

* Added ability to enter an email subject for the "email" field type (only when outputted as mailto anchor);
* When caching CSS and Javascript, form validator library could not load the "file" and "date" validator which in turn caused to not validate at all. This is fixed by requiring the file.js with requireAsset;
* Block Configurations can now be loaded by going to "Stacks & Blocks" - "Block Designer" - "Block Config". This page will have the available block types clickable (only when the config.json file exists);
* Block Types within a package that do have a config.json file (and the package is installed), can now also be loaded;

#v1.0.5

* Colorpicker field type now uses Concrete5/core spectrum.css and spectrum.js files (related CSS/JS files deleted);
* Bootstrap Fonts (glyphicons) replaced with Font Awesome (all CSS related files deleted);
* Remaining "renderjs" related files and lines deleted, because this was completely rewritten to "handlebarsjs";
* Caching CSS and JavaScript fix - when this function was turned on (yoursite.com/dashboard/system/optimization/cache), some jQuery functions where not executed, resulting in a non-working page;
* Email field type can now be outputted as anchor with "mailto" functionality + ability to add one or multiple classes to this anchor;
* Parsedown library update (original) - https://github.com/erusev/parsedown;
* Fixed error notice if multiple blocks used the "markdown" field type on the same page;
* Markdown now returns full HTML as "searchable content" instead of plain markdown;

#v1.0.4

* New field type: stacks;
* Clean(er) output of "db.xml" file;
* Extra blacklisted slugs ('file', 'bid', 'bttable', 'helpers', 'btfieldsrequired', 'btinterfacewidth', 'btinterfaceheight', 'btcacheblockrecord', 'btcacheblockoutput', 'btcacheblockoutputonpost', 'btcacheblockoutputforregisteredusers', 'btcacheblockoutputlifetime', 'bthandle', 'btname', 'btexportpagecolumns', 'btexportfilecolumns', 'btexportpagetypecolumns', 'btexportpagefeedcolumns', 'btwrapperclass'), which will cause problems with Concrete5 core, searching for a different (edit) file or overwriting/using wrong data;
* Email field type fix where if no email is being entered, it would give you the notice of an invalid email being entered - so blank values are possible from now on;
* Developers: "getSearchableContent" now needs to return lines of PHP where searchable values can be stored into the "$content" array (like so: $content[] = $this->theFieldSlug);
* Developers: new field type function: "deleteFunctionContents" and "duplicateFunctionContents";
* Developers: "view", "delete" and "duplicate" functions always load the database in a "$db" variable by default (if there were lines returned by field types for these functions);
* Developers: "on_start" function always load the asset list instance in a "$al" variable by default (if there were lines returned by field types for this function);
* Developers: blockType table name passed along to each function of a field type;
* Developers: removed field types' "dbXML" function and replaced this with a "dbFields" function, which will return an array of fields instead of a string of the XML field;
* Developers: new function "dbTables" which will give functionality to create one or multiple tables within a field type (the foundation for so called "entries" like the image_slider block has);

#v1.0.3

* New field type: color_picker;
* Fixed "Block type <handle> does not exist (anymore)." upon direct installing block types on live servers - live it would search for Core blocks instead of "Application" blocks;
* Fixed class names not being escaped in the URL field type because of double quoted string (class="your-class" instead of class=\"your-class\") - thank you "nickratering" for mentioning this;
* Developers: Edited "date_time" field type's inline CSS/JS to Concrete5 "requireAsset" standards;
* Developers: The block handle (block_handle) will now be passed to field types within the "data" array;
* Developers: Added "on_startFunctionContents" function for field types, i.e. for asset registering;
* Developers: Added "defined or die statement" to Parsedown library - within "markdown" fieldtype;

#v1.0.2

* New field type: file;
* New field type: youtube;
* Full rewrite from renderjs to Handelebars(js), which is much more powerful - will help A LOT when writing field options for each field type;
* "Text box", "Select" and "URL" field type options/fields now wrapped in div with class "content-field-options";
* Added the ability to enter (a) class name(s) to your "URL" field type links;

#v1.0.1.1

* WYSIWYG field type fix should really work now (was a long day I guess);

#v1.0.1

* Label for (clickable) fix for "Cache block output lifetime" field in "Advanced" tab;
* Placeholder field option added for "Text box" field type;
* Fallback value field option added for "Text box" field type (when a field is being left blank, this value will be used - usefull for i.e. "read more" text);
* Removed extra white spaces in front of "use" statements (controller.php);
* WYSIWYG field type fix - content was not being saved because of recent ID prefixing;

#v1.0.0

* New field type: markdown;
* Added ability to have a page (link) anchor on the "image" field type and have one or multiple classes attached to this anchor;
* Date_time change to not include CSS/JS on default (as files within js/css directories will automatically get included by Concrete5) by renaming folders;
* Last version number got stuck on 0.9.8, so that may have caused to keep showing the "update addon" button, sorry;
* No more digits allowed in block handle, because namespaces can not (always) use them;
* Removed extra functions (translateFromEditMode) from WYSIWYG field type and rewrote those functions to use the core functions of the "LinkAbstractor" class;
* Prefixed the id attribute of the WYSIWYG field type with "wysiwyg-ft-" in order to maintain all editor functions - thank you "j3ns" for mentioning this;
* Renamed "Cache" tab name to "Advanced", because this tab will have more fields as of now;
* Added an optional "Table prefix" field in the (recently renamed) "Advanced" tab, which will create a table like this "bt<prefix><handle>" (only if the field is filled of course);

#v0.9.9

* New field type: code;
* Removed indenting/spaces before/after prefix/suffix of fields, in order to have everything appended after eachother (needed in some cases);
* Updated link field type validation - thank you "yulolimum" for mentioning possible flaws;
* Sanitize (htmlspecialchars()) output for text_box, text_area and email field types to reduce the risks of XSS (cross site scripting);
* Newly created blocks with a dash (-) used in the handle would result in errors. Dashes get converted into underscores (_) now and multiple/double underscores will be replaced by a single underscore;

#v0.9.8

* Ability to enter class(es) to be added to your image field type;
* Ability to make a thumbnail of your image field type, by entering the wanted width and height;
* Ability to crop your image field type (only possible when making it a thumbnail);

#v0.9.7

* New field type: email;
* New field type: date_time;
* Added ability to sort field types on the block designer index page by various functions, standard sorting changed to alphabetical instead of user defined key sorting (uksort);
* Upon adding a new field when creating a new block, first visible "input", "textarea" or "select" (dropdown) will get focused;
* Animation added for adding fields when creating a block, so they don't magically appear in the list;
* Animation added for deleting fields when creating a block, so they don't magically disappear from the list;
* Better error handling and notification upon installing block types using the build in "Direct install" functionality;
* Fixed a bug when a non-existing field type was being posted (should not happen though, when not manipulating HTML or deleting field types);
* Fixed forgotten t() function for error messages "One or multiple fields are required to build a block." and "-- None --";
* Fixed a bug where the "delete this block type folder" link wouldn't get the correct path when loading a config of a block;
* Code cleaning and optimization to increase speed for both client-side and server-side;
* Developers: New "copyFiles" function for field types, which gives you the option to copy files from [source] to [target] (with [mode]) - only executed when no field type errors available;

#v0.9.6

* Fixed a bug where ft_count for each field type would always reset to 0 over and over (causing code to double include, for example the WYSIWYG field);
* New field type: number;
* JavaScript optimization;
* Minified all packaged CSS to speed up loading and standalone bootstrap fonts and responsive tabs CSS file to be re-used with other pages/field types;
* When changing the field order (sorting fields), the placeholder has the height of the currently dragging item instead of a fixed height which caused the page to change in height;
* Fix when dragging/sorting the first field, it would get a margin at the top as well;
* Fixed bug where all divs with "alert" classes would get instantly hidden within the div .block-designer-container;
* Colored options block for fields that have too many options (number);
* More space/margin added between fields in the block designer configuration page;
* Select field type update: set your own array key for values, by using 2 semicolons (" :: ") on each line - extra spaces required;
* Renamed "Image" field under the interface tab to "Icon" in order to better explain it's use;
* Developers: New "fieldOptionsJavascript" function for field types, which gives you the option to pass in field type specific Javascript;
* Developers: New "getFieldNote" function for field types, which gives you the option to add a note (i.e. telling the user "You need a Twitter account first" for a specific field)
* Developers: Rewrote old if(): endif: statements to {} in each field type's "viewContents" function;

#v0.9.5

* Each field type has it's own icon.png file, which will show up in the "Add a field" section. This makes it easier to see what to click, instead of text links;
* After submitting the form with (processed) errors, the form gets fully populated with the given values and fields;
* After having created a block with the block designer, go to `index.php/dashboard/blocks/block_designer/config/[YOUR_BLOCK_HANDLE_HERE]` to reload all of the entered values. This way you can create a similar block or add another field and delete the old block very FAST;
* Developers: field type "validate" function now returns true OR a string, where everything else but true will be an error string. Creating a block won't succeed and give a message with the returned string;
* Developers: field type "getSearchableContent" function added to be able to return some (unprocessed) PHP. See the "text_box" field type for usage;
* Developers: each field type function with $data array passed along, will now have each value directly in the array. Upon new field type creation, do not use these standard keys: row_id, slug, required, prefix, suffix, label, ft_count;

#v0.9.1 - v0.9.4

* Minor bugfixes and code improvements

#v0.9.0

* Initial Release