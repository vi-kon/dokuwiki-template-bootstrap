<?php

if (!defined('DOKU_INC'))
{
    die;
}

global $ID;
global $INFO;
global $conf;
/** @var DokuWiki_Auth_Plugin $auth */
global $auth;

$pluginList = plugin_list('admin');
$menu       = array();
foreach ($pluginList as $plugin)
{
    /** @var DokuWiki_Admin_Plugin $obj */
    if (($obj = plugin_load('admin', $plugin)) === null)
    {
        continue;
    }

    // check permissions
    if ($obj->forAdminOnly() && !$INFO['isadmin'])
    {
        continue;
    }

    $menu[$plugin] = array(
        'plugin' => $plugin,
        'prompt' => $obj->getMenuText($conf['lang']),
        'sort'   => $obj->getMenuSort()
    );
}

?>

<?php echo p_locale_xhtml('admin'); ?>

    <div class="row">
        <?php if ($INFO['isadmin']): ?>
            <div class="col-sm-6">
                <div class="list-group">

                    <?php if ($menu['usermanager'] && $auth && $auth->canDo('getUsers')): ?>
                        <a class="list-group-item" href="<?php echo wl($ID, array('do' => 'admin', 'page' => 'usermanager')) ?>">
                            <span class="glyphicon glyphicon-user"></span>
                            <?php echo $menu['usermanager']['prompt'] ?>
                        </a>
                    <?php endif ?>
                    <?php unset($menu['usermanager']) ?>

                    <?php if ($menu['acl']): ?>
                        <a class="list-group-item" href="<?php echo wl($ID, array('do' => 'admin', 'page' => 'acl')) ?>">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                            <?php echo $menu['acl']['prompt'] ?>
                        </a>
                    <?php endif ?>
                    <?php unset($menu['acl']) ?>

                    <?php if ($menu['extension']): ?>
                        <a class="list-group-item" href="<?php echo wl($ID, array('do' => 'admin', 'page' => 'extension')) ?>">
                            <span class="glyphicon glyphicon-tasks"></span>
                            <?php echo $menu['extension']['prompt'] ?>
                        </a>
                    <?php endif ?>
                    <?php unset($menu['extension']) ?>

                    <?php if ($menu['config']): ?>
                        <a class="list-group-item" href="<?php echo wl($ID, array('do' => 'admin', 'page' => 'config')) ?>">
                            <span class="glyphicon glyphicon-cog"></span>
                            <?php echo $menu['config']['prompt'] ?>
                        </a>
                    <?php endif ?>
                    <?php unset($menu['config']) ?>

                </div>
            </div>
        <?php endif // isadmin ?>

        <div class="col-sm-6">
            <div class="list-group">

                <?php if ($menu['revert']): ?>
                    <a class="list-group-item" href="<?php echo wl($ID, array('do' => 'admin', 'page' => 'revert')) ?>">
                        <span class="glyphicon glyphicon-hdd"></span>
                        <?php echo $menu['revert']['prompt'] ?>
                    </a>
                <?php endif ?>
                <?php unset($menu['revert']) ?>

                <?php if ($menu['popularity']): ?>
                    <a class="list-group-item" href="<?php echo wl($ID, array('do' => 'admin', 'page' => 'popularity')) ?>">
                        <span class="glyphicon glyphicon-calendar"></span>
                        <?php echo $menu['popularity']['prompt'] ?>
                    </a>
                <?php endif ?>
                <?php unset($menu['popularity']) ?>

            </div>
        </div>
    </div>

    <div id="admin__version">
        <?php echo getVersion() ?>
    </div>

<?php if (count($menu)): ?>
    <?php usort($menu, 'p_sort_modes') ?>

    <?php echo p_locale_xhtml('adminplugins'); ?>

    <ul class="list-group">
        <?php foreach ($menu as $item): ?>

            <?php if (!$item['prompt']): ?>
                <?php continue; ?>
            <?php endif ?>

            <a class="list-group-item" href="<?php echo wl($ID, 'do=admin&amp;page=' . $item['plugin']) ?>">
                <span class="glyphicon glyphicon-arrow-right"></span>
                <?php echo $item['prompt'] ?>
            </a>

        <?php endforeach ?>
    </ul>
<?php endif ?>