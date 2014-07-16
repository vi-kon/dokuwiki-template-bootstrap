<?php

global $INPUT;
global $ID;
global $REV;
global $DATE;
global $PRE;
global $SUF;
global $INFO;
global $SUM;
global $lang;
global $conf;
global $TEXT;
global $RANGE;

if ($INPUT->has('changecheck'))
{
    $check = $INPUT->str('changecheck');
}
elseif (!$INFO['exists'])
{
// $TEXT has been loaded from page template
    $check = md5('');
}
else
{
    $check = md5($TEXT);
}
$mod = md5($TEXT) !== $check;

$wr      = $INFO['writable'] && !$INFO['locked'];
$include = 'edit';
if ($wr)
{
    if ($REV)
    {
        $include = 'editrev';
    }
}
else
{
// check pseudo action 'source'
    if (!actionOK('source'))
    {
        msg('Command disabled: source', -1);

        return;
    }
    $include = 'read';
}

global $license;

$form = new Doku_Form(array('id' => 'dw__editform'));
$form->addHidden('id', $ID);
$form->addHidden('rev', $REV);
$form->addHidden('date', $DATE);
$form->addHidden('prefix', $PRE . '.');
$form->addHidden('suffix', $SUF);
$form->addHidden('changecheck', $check);

$data = array(
    'form'          => $form,
    'wr'            => $wr,
    'media_manager' => true,
    'target'        => ($INPUT->has('target') && $wr)
        ? $INPUT->str('target')
        : 'section',
    'intro_locale'  => $include
);

if ($data['target'] !== 'section')
{
// Only emit event if page is writable, section edit data is valid and
// edit target is not section.
    trigger_event('HTML_EDIT_FORMSELECTION', $data, 'html_edit_form', true);
}
else
{
    html_edit_form($data);
}
if (isset($data['intro_locale']))
{
    echo p_locale_xhtml($data['intro_locale']);
}

$form->addHidden('target', $data['target']);
$form->addElement(form_makeOpenTag('div', array('id' => 'wiki__editbar', 'class' => 'editBar')));
$form->addElement(form_makeOpenTag('div', array('id' => 'size__ctl')));
$form->addElement(form_makeCloseTag('div'));
if ($wr)
{
    $form->addElement(form_makeOpenTag('div', array('class' => 'form-group editButtons')));
    $form->addElement(form_makeButton('submit', 'save', $lang['btn_save'], array('id' => 'edbtn__save', 'class' => 'btn btn-success', 'accesskey' => 's', 'tabindex' => '4')));
    $form->addElement(form_makeButton('submit', 'preview', $lang['btn_preview'], array('id' => 'edbtn__preview', 'class' => 'btn btn-default', 'accesskey' => 'p', 'tabindex' => '5')));
    $form->addElement(form_makeButton('submit', 'draftdel', $lang['btn_cancel'], array('class' => 'btn btn-danger', 'tabindex' => '6')));
    $form->addElement(form_makeCloseTag('div'));
    $form->addElement(form_makeOpenTag('div', array('class' => 'summary')));
    $form->addElement(form_makeTextField('summary', $SUM, $lang['summary'], 'edit__summary', 'nowrap', array('size' => '50', 'tabindex' => '2')));
    $elem = html_minoredit();
    if ($elem)
    {
        $form->addElement($elem);
    }
    $form->addElement(form_makeCloseTag('div'));
}
$form->addElement(form_makeCloseTag('div'));
if ($wr && $conf['license'])
{
    $form->addElement(form_makeOpenTag('div', array('class' => 'license')));
    $out = $lang['licenseok'];
    $out .= ' <a href="' . $license[$conf['license']]['url'] . '" rel="license" class="urlextern"';
    if ($conf['target']['extern'])
    {
        $out .= ' target="' . $conf['target']['extern'] . '"';
    }
    $out .= '>' . $license[$conf['license']]['name'] . '</a>';
    $form->addElement($out);
    $form->addElement(form_makeCloseTag('div'));
}

if ($wr): ?>

    <script type="text/javascript">/*<![CDATA[*/
        textChanged = <?php echo ($mod
                   ? 'true'
                   : 'false') ?>;
        /*!]]>*/</script>
<?php endif ?>
<div class="editBox" role="application">

    <div class="toolbar group">
        <div id="draft__status" class="alert alert-info">
            <?php if (!empty($INFO['draft'])):
                echo $lang['draftdate'] . ' ' . dformat();
            endif ?>
        </div>
        <div id="tool__bar">
            <?php if ($wr && $data['media_manager']): ?>
                <a href="<?php echo DOKU_BASE ?>lib/exe/mediamanager.php?ns=<?php echo $INFO['namespace'] ?>"
                   target="_blank"><?php echo $lang['mediaselect'] ?></a>
            <?php endif ?>
        </div>
    </div>
<?php

html_form('edit', $form);
print '</div>' . NL;