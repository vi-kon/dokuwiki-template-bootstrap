<?php

if (!defined('DOKU_INC'))
{
    die;
}

global $lang;
global $conf;
global $INPUT;
global $INFO;
/** @var DokuWiki_Auth_Plugin $auth */
global $auth;

$fullname = $INPUT->post->str('fullname', $INFO['userinfo']['name'], true);
$email    = $INPUT->post->str('email', $INFO['userinfo']['mail'], true);

?>

<?php echo p_locale_xhtml('updateprofile') ?>

    <h2><?php echo $lang['profile'] ?></h2>
    <hr/>

    <form class="form-horizontal" action="" method="post">
        <input type="hidden" name="do" value="profile"/>
        <input type="hidden" name="save" value="1"/>
        <input type="hidden" name="sectok" value="<?php echo getSecurityToken() ?>"/>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input_login">
                <?php echo $lang['user'] ?>
            </label>

            <div class="col-sm-10">
                <input id="input_login" class="form-control" type="text" name="login" value="<?php echo $_SERVER['REMOTE_USER'] ?>" disabled="disabled"/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input_fullname">
                <?php echo $lang['fullname'] ?>
            </label>

            <div class="col-sm-10">
                <input id="input_fullname" class="form-control" type="text" name="fullname" value="<?php echo $fullname ?>"<?php if (!$auth->canDo('modName')): ?> disabled="disabled"<?php endif ?>/>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label" for="input_email">
                <?php echo $lang['email'] ?>
            </label>

            <div class="col-sm-10">
                <input id="input_email" class="form-control" type="text" name="email" value="<?php echo $email ?>"<?php if (!$auth->canDo('modMail')): ?> disabled="disabled"<?php endif ?>/>
            </div>
        </div>

        <?php if ($auth->canDo('modPass')): ?>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input_newpass">
                    <?php echo $lang['newpass'] ?>
                </label>

                <div class="col-sm-10">
                    <input id="input_newpass" class="form-control" type="password" name="newpass"/>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label" for="input_passchk">
                    <?php echo $lang['passchk'] ?>
                </label>

                <div class="col-sm-10">
                    <input id="input_passchk" class="form-control" type="password" name="passchk"/>
                </div>
            </div>
        <?php endif ?>

        <?php if ($conf['profileconfirm']): ?>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input_oldpass">
                    <?php echo $lang['oldpass'] ?>
                </label>

                <div class="col-sm-10">
                    <input id="input_oldpass" class="form-control" type="password" name="oldpass"/>
                </div>
            </div>
        <?php endif ?>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input class="btn btn-primary" type="submit" value="<?php echo $lang['btn_save'] ?>"/>
                <input class="btn btn-danger" type="reset" value="<?php echo $lang['btn_reset'] ?>"/>
            </div>
        </div>
    </form>

<?php if ($auth->canDo('delUser') && actionOK('profile_delete')): ?>

    <h2><?php echo $lang['profdeleteuser'] ?></h2>
    <hr/>

    <form class="form-horizontal" action="" method="post">
        <input type="hidden" name="do" value="profile_delete"/>
        <input type="hidden" name="delete" value="1"/>
        <input type="hidden" name="sectok" value="<?php echo getSecurityToken() ?>"/>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="confirm_delete" value="1"/>
                        <?php echo $lang['profconfdelete'] ?>
                    </label>
                </div>
            </div>
        </div>

        <?php if ($conf['profileconfirm']): ?>
            <div class="form-group">
                <label class="col-sm-2 control-label" for="input_oldpass">
                    <?php echo $lang['oldpass'] ?>
                </label>

                <div class="col-sm-10">
                    <input id="input_oldpass" class="form-control" type="password" name="oldpass"/>
                </div>
            </div>
        <?php endif ?>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <input class="btn btn-danger" type="submit" value="<?php echo $lang['btn_deleteuser'] ?>"/>
            </div>
        </div>

    </form>
<?php endif ?>
