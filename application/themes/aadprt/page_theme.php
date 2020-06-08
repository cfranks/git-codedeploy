<?php 
namespace Application\Theme\Aadprt;

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
					'<div class="col-sm-12 col-md-6"></div>',
					'<div class="col-sm-12 col-md-6"></div>'
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
				'img-circle',
				'text',
				'img-equal'
			)
		);
	}
	public function getThemeEditorClasses()
	{
		return array(
			array('title' => t('Button'), 'menuClass' => '', 'spanClass' => 'btn-theme', 'forceBlock' => 1),
			array('title' => t('Text Button'), 'menuClass' => '', 'spanClass' => 'btn-text', 'forceBlock' => 1),
			array('title' => t('File Link'), 'menuClass' => '', 'spanClass' => 'btn-file', 'forceBlock' => 1),
			array('title' => t('Img Right'), 'menuClass' => '', 'spanClass' => 'img-right', 'forceBlock' => 1),
			array('title' => t('Img Left'), 'menuClass' => '', 'spanClass' => 'img-left', 'forceBlock' => 1),
			array('title' => t('Lead'), 'menuClass' => '', 'spanClass' => 'lead', 'forceBlock' => 1),
			array('title' => t('Preheader'), 'menuClass' => '', 'spanClass' => 'preheader', 'forceBlock' => 1)
			
		);
	}
}

