<?php

if (!defined('DOKU_INC'))
{
    die;
}

global $lang;
global $conf;
global $ACT;
global $QUERY;

include_once __DIR__ . DIRECTORY_SEPARATOR . 'tpl_functions.php';

?>

<!DOCTYPE html>
<?php /* @formatter:off */ ?>
<!--[if lt IE 7]>
<html class="lt-ie9 lt-ie8 lt-ie7" lang="<?php echo $conf['lang'] ?>"> <![endif]-->
<!--[if IE 7]>
<html class="lt-ie9 lt-ie8" lang="<?php echo $conf['lang'] ?>"> <![endif]-->
<!--[if IE 8]>
<html class="lt-ie9" lang="<?php echo $conf['lang'] ?>"> <![endif]-->
<!--[if gt IE 8]><!-->
<?php /* @formatter:on */ ?>
<html lang="<?php echo $conf['lang'] ?>"><!--<![endif]-->
<head>
    <title><?php tpl_pagetitle() ?> [<?php echo strip_tags($conf['title']) ?>]</title>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <?php tpl_metaheaders() ?>
    <?php echo tpl_favicon(array('favicon', 'mobile')) ?>
    <?php tpl_includeFile('meta.html') ?>
    <link rel="stylesheet" href="<?php echo tpl_basedir(); ?>css/bootstrap.min.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="<?php echo tpl_basedir(); ?>css/icon-io.css" type="text/css" media="all"/>
    <link rel="stylesheet" href="<?php echo tpl_basedir(); ?>css/style.css" type="text/css" media="all"/>
    <script type="text/javascript" src="<?php echo tpl_basedir(); ?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo tpl_basedir(); ?>js/setup.js"></script>
</head>
<body data-spy="scroll" data-target=".navbar-toc">
<div class="container">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h1>
                    <a href="<?php echo wl() ?>"><?php echo $conf['title'] ?></a>
                </h1>
            </div>
            <?php if ($conf['useacl']): ?>
                <div class="col-sm-6 text-right">
                    <?php if (!empty($_SERVER['REMOTE_USER'])): ?>
                        <?php echo userlink() ?>
                        &nbsp;|&nbsp;
                    <?php endif ?>

                    <?php if (vkb_tpl_actionlink('admin')): ?>
                        &nbsp;|&nbsp;
                    <?php endif ?>

                    <?php if (vkb_tpl_actionlink('profile')): ?>
                        &nbsp;|&nbsp;
                    <?php endif ?>

                    <?php if (vkb_tpl_actionlink('register')): ?>
                        &nbsp;|&nbsp;
                    <?php endif ?>

                    <?php vkb_tpl_actionlink('login'); ?>
                </div>
            <?php endif ?>

            <?php if (actionOK('search')): ?>
                <div class="col-sm-6 text-right search-form">
                    <form id="dw__search" class="form-inline" action="<?php echo wl() ?>" accept-charset="utf-8" method="get" role="search">
                        <input type="hidden" name="do" value="search"/>
                        <input id="qsearch__in" class="form-control input-sm" type="text" name="id"<?php if ($ACT == 'search'): ?> value="<?php htmlspecialchars($QUERY) ?>" <?php endif ?>/>
                        <input class="btn btn-sm btn-primary" type="submit" value="<?php echo $lang['btn_search'] ?>"/>
                    </form>
                </div>
            <?php endif ?>

        </div><?php // row ?>

    </div><?php // page-header ?>

    <?php vkb_html_msgarea() ?>

    <?php vkb_tpl_content() ?>
    <?php tpl_flush() ?>

    <div class="text-right">
        <hr/>
        <?php $firstAction = true ?>
        <?php if (tpl_get_action('edit')): ?>
            <?php if ($firstAction): ?>
                <?php $firstAction = false ?>
            <?php else: ?>
                &nbsp;|&nbsp;
            <?php endif ?>
            <?php vkb_tpl_actionlink('edit') ?>
        <?php endif ?>

        <?php if (tpl_get_action('revisions')): ?>
            <?php if ($firstAction): ?>
                <?php $firstAction = false ?>
            <?php else: ?>
                &nbsp;|&nbsp;
            <?php endif ?>
            <?php vkb_tpl_actionlink('revisions') ?>
        <?php endif ?>

        <?php if (tpl_get_action('backlink')): ?>
            <?php if ($firstAction): ?>
                <?php $firstAction = false ?>
            <?php else: ?>
                &nbsp;|&nbsp;
            <?php endif ?>
            <?php vkb_tpl_actionlink('backlink') ?>
        <?php endif ?>

        <?php if (tpl_get_action('subscribe')): ?>
            <?php if ($firstAction): ?>
                <?php $firstAction = false ?>
            <?php else: ?>
                &nbsp;|&nbsp;
            <?php endif ?>
            <?php vkb_tpl_actionlink('subscribe') ?>
        <?php endif ?>

        <?php if (tpl_get_action('revert')): ?>
            <?php if ($firstAction): ?>
                <?php $firstAction = false ?>
            <?php else: ?>
                &nbsp;|&nbsp;
            <?php endif ?>
            <?php vkb_tpl_actionlink('revert') ?>
        <?php endif ?>

        <?php if ($firstAction): ?>
            <?php $firstAction = false ?>
        <?php else: ?>
            &nbsp;|&nbsp;
        <?php endif ?>
        <?php vkb_tpl_actionlink('top') ?>

    </div>

    <div class="page-footer">
        <hr/>
        <div class="text-center text-muted">
            <div class="doc"><?php tpl_pageinfo() ?></div>
            <?php tpl_license('button'); ?>
        </div>
    </div><?php //page-footer ?>

</div>
<?php tpl_indexerWebBug() ?>
</body>
</html>