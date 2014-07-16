<?php

if (!defined('DOKU_INC'))
{
    die;
}

global $lang;
global $conf;
global $ID;
global $INPUT;
?>

<?php echo p_locale_xhtml('login') ?>

<form class="form-horizontal" action="" method="post">
    <input type="hidden" name="sectok" value="<?php echo getSecurityToken() ?>"/>
    <input type="hidden" name="id" value="<?php echo $ID ?>"/>
    <input type="hidden" name="do" value="login"/>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="input_u">
            <?php echo $lang['user'] ?>
        </label>

        <div class="col-sm-10">
            <input id="input_u" class="form-control" type="text" name="u" value="<?php (!$INPUT->bool('http_credentials')
                ? $INPUT->str('u')
                : '') ?>"/>
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-2 control-label" for="input_p">
            <?php echo $lang['pass'] ?>
        </label>

        <div class="col-sm-10">
            <input id="input_p" class="form-control" type="password" name="p"/>
        </div>
    </div>

    <?php if ($conf['rememberme']): ?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                    <label>
                        <input name="r" type="checkbox" value="1"> <?php echo $lang['remember'] ?>
                    </label>
                </div>
            </div>
        </div>
    <?php endif ?>

    <div class="form-group">
        <div class="col-sm-offset-2 col-sm-4">
            <input class="btn btn-primary" type="submit" value="<?php echo $lang['btn_login'] ?>"/>
        </div>
        <div class="col-sm-6">
            <?php if (actionOK('register')): ?>
                <p><?php echo $lang['reghere'] ?>: <?php tpl_actionlink('register') ?></p>
            <?php endif ?>
            <?php if (actionOK('resendpwd')): ?>
                <p><?php echo $lang['pwdforget'] ?>: <?php tpl_actionlink('resendpwd') ?></p>
            <?php endif ?>
        </div>
    </div>
</form>
