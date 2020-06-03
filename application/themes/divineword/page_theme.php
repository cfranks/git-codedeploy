<?php 
namespace Application\Theme\Divineword;

use Concrete\Core\Page\Theme\Theme;
use Concrete\Core\Area\Layout\Preset\Provider\ThemeProviderInterface;

class PageTheme extends Theme implements ThemeProviderInterface {
	
	protected $pThemeGridFrameworkHandle = 'bootstrap3';
	public function getThemeAreaLayoutPresets()
	{
		$presets = array(
			array(
				'handle' => 'tablet_stack',
				'name' => 'Tablet Stack',
				'container' => '<div class="row"></div>',
				'columns' => array(
					'<div class="col-sm-12 col-md-3"></div>',
					'<div class="col-sm-12 col-md-9"></div>'
				),
			),
			array(
				'handle' => 'tablet_stack_fourcol',
				'name' => 'Tablet Stack Four Col',
				'container' => '<div class="row"></div>',
				'columns' => array(
					'<div class="col-sm-6 col-md-3"></div>',
					'<div class="col-sm-6 col-md-3"></div>',
					'<div class="col-sm-6 col-md-3"></div>',
					'<div class="col-sm-6 col-md-3"></div>'
				),
			),
			array(
				'handle' => 'three_cols',
				'name' => 'Three Columns',
				'container' => '<div class="row"></div>',
				'columns' => array(
					'<div class="col-sm-4"></div>',
					'<div class="col-sm-4"></div>',
					'<div class="col-sm-4"></div>'
				),
			),
			array(
				'handle' => 'five_cols',
				'name' => 'Five Columns',
				'container' => '<div class="row five-cols"></div>',
				'columns' => array(
					'<div class="col"></div>',
					'<div class="col"></div>',
					'<div class="col"></div>',
					'<div class="col"></div>',
					'<div class="col"></div>'
				),
			),
			array(
				'handle' => 'text_grid',
				'name' => 'Text and Grid',
				'container' => '<div></div>',
				'columns' => array(
					'<div class="col text"></div>',
					'<div class="col grid"></div>'
				),
			)
		);
		return $presets;
	}
	public function getThemeBlockClasses()
	{
		return array(
			'*' => array(
				'fun-list',
				'box',
				'box-simple',
				'fun-img'
			)
		);
	}
	public function getThemeEditorClasses()
	{
		return array(
			array('title' => t('Button'), 'menuClass' => '', 'spanClass' => 'btn-theme', 'forceBlock' => 1),
			array('title' => t('Text Button'), 'menuClass' => '', 'spanClass' => 'btn-text', 'forceBlock' => 1),
			array('title' => t('Preheader'), 'menuClass' => '', 'spanClass' => 'preheader', 'forceBlock' => 1),
			array('title' => t('Lead'), 'menuClass' => '', 'spanClass' => 'lead', 'forceBlock' => 1),
			array('title' => t('Small Lead'), 'menuClass' => '', 'spanClass' => 'smalllead', 'forceBlock' => 1),
			array('title' => t('Quote'), 'menuClass' => '', 'spanClass' => 'quote', 'forceBlock' => 1),
			array('title' => t('Img Right'), 'menuClass' => '', 'spanClass' => 'img-right', 'forceBlock' => 1),
			array('title' => t('Img Left'), 'menuClass' => '', 'spanClass' => 'img-left', 'forceBlock' => 1),
			array('title' => t('Border Left'), 'menuClass' => '', 'spanClass' => 'border-left', 'forceBlock' => 1),
		);
	}
}

