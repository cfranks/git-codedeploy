<?php

/**
 * -----------------------------------------------------------------------------
 * Generated 2020-06-02T10:21:50-05:00
 *
 * @item      misc.latest_version
 * @group     concrete
 * @namespace null
 * -----------------------------------------------------------------------------
 */
return array(
    'site' => 'AADPRT',
    'version_installed' => '5.7.4.2',
    'misc' => array(
        'access_entity_updated' => 1442523692,
        'latest_version' => '5.7.5.13',
        'do_page_reindex_check' => false,
        'favicon_fid' => '9'
    ),
    'cache' => array(
        'blocks' => true,
        'assets' => true,
        'theme_css' => true,
        'overrides' => true,
        'pages' => '0',
        'full_page_lifetime' => 'default',
        'full_page_lifetime_value' => null
    ),
    'theme' => array(
        'compress_preprocessor_output' => true
    ),
    'seo' => array(
        'canonical_url' => '',
        'canonical_ssl_url' => '',
        'redirect_to_canonical_url' => 0,
        'url_rewriting' => 1,
        'tracking' => array(
            'code' => '<script>
  (function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');

  ga(\'create\', \'UA-111495863-1\', \'auto\');
  ga(\'send\', \'pageview\');

</script>',
            'code_position' => 'top'
        )
    ),
    'editor' => array(
        'concrete' => array(
            'enable_filemanager' => '1',
            'enable_sitemap' => '1'
        ),
        'plugins' => array(
            'selected' => array(
                'undoredo',
                'concrete5lightbox',
                'specialcharacters',
                'table',
                'fontsize'
            )
        )
    ),
    'debug' => array(
        'detail' => 'debug',
        'display_errors' => true
    ),
    'session' => array(
        'name' => 'CONCRETE5',
        'handler' => 'file',
        'max_lifetime' => 7200,
        'cookie' => array(
            'cookie_path' => false,
            'cookie_lifetime' => 0,
            'cookie_domain' => false,
            'cookie_secure' => true,
            'cookie_httponly' => true
        )
    )
);
