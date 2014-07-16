<?php

if (!defined('DOKU_INC'))
{
    die;
}

global $ID;
global $REV;
global $HIGH;
global $INFO;
//disable section editing for old revisions or in preview

$secedit = !$REV;

if ($REV)
{
    print p_locale_xhtml('showrev');
}
$html = p_wiki_xhtml($ID, $REV, true);
$html = vkb_html_secedit($html, $secedit);
if ($INFO['prependTOC'])
{
    $html = '<div class="row"><div class="col-sm-9">' . $html . '</div>';
    $html .= '<div class="col-sm-3">' . vkb_tpl_toc(true) . '</div></div>';
}
$html = html_hilight($html, $HIGH);
echo $html;
