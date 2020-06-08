<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>
<style type="text/css">
    #blog-post-form{padding: 22px;}
</style>
<?php  
Loader::PackageElement(
    'tools/add_blog',
    'problog',
    array(
        'blog' => $blog,
        'blogTitle'=>$blogTitle,
        'blogDescription'=>$blogDescription,
        'blogBody'=>$blogBody,
        'sections' => $sections,
        'pageTypes' => $pageTypes,
        'buttonText'=>$buttonText,
        'loadScripts'=>false
    )
);
?>
