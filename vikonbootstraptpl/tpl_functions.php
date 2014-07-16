<?php

if (!defined('DOKU_INC'))
{
    die;
}
function vkb_tpl_content()
{
    global $ACT;
    global $INFO;
    $INFO['prependTOC'] = true;

    ob_start();
    trigger_event('TPL_ACT_RENDER', $ACT, 'vkb_tpl_content_core');
    $html_output = ob_get_clean();
    trigger_event('TPL_CONTENT_DISPLAY', $html_output, 'ptln');

    return !empty($html_output);
}

function vkb_tpl_content_core()
{
    global $ACT;
    global $TEXT;
    global $PRE;
    global $SUF;
    global $SUM;
    global $IDX;
    global $INPUT;

    switch ($ACT)
    {
        case 'show':
            include __DIR__ . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'show.php';
            break;

        case 'edit':
        case 'recover':
            include __DIR__ . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'edit.php';
            break;

        case 'preview':
            include __DIR__ . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'edit.php';
            html_show($TEXT);
            break;

        case 'login':
            include __DIR__ . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'login.php';
            break;

        case 'profile':
            include __DIR__ . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'profile.php';
            break;

        case 'admin':
            vkb_tpl_admin();
            break;

        default:
            return tpl_content_core();
    }

    return true;
}

function vkb_tpl_admin()
{
    global $INFO;
    global $TOC;
    global $INPUT;

    /** @var $plugin DokuWiki_Admin_Plugin */
    $plugin   = null;
    $class    = $INPUT->str('page');
    $redirect = array(
        'config'    => 'vikonconfig',
        'extension' => 'vikonextension',
    );

    if (!empty($class))
    {
        $pluginList = plugin_list('admin');

        if (array_key_exists($class, $redirect) && in_array($redirect[$class], $pluginList))
        {
            $plugin = plugin_load('admin', $redirect[$class]);
        }
        elseif (in_array($class, $pluginList))
        {
            $plugin = plugin_load('admin', $class);
        }
    }

    if ($plugin !== null)
    {
        if (!is_array($TOC))
        {
            $TOC = $plugin->getTOC();
        } //if TOC wasn't requested yet

        if ($INFO['prependTOC'] && ($html = vkb_tpl_toc(true)) != '')
        {
            echo '<div class="row">';
            echo '<div class="col-sm-9">';
            $plugin->html();
            echo '</div>';
            echo '<div class="col-sm-3">';
            echo $html;
            echo '</div>';
            echo '</div>';
        }
        else
        {
            $plugin->html();
        }
    }
    else
    {
        include __DIR__ . DIRECTORY_SEPARATOR . 'html' . DIRECTORY_SEPARATOR . 'admin.php';
    }

    return true;
}

function vkb_tpl_toc($return = false)
{
    global $TOC;
    global $ACT;
    global $ID;
    global $REV;
    global $INFO;
    global $conf;
    global $INPUT;
    $toc = array();

    if (is_array($TOC))
    {
        // if a TOC was prepared in global scope, always use it
        $toc = $TOC;
    }
    elseif (($ACT == 'show' || substr($ACT, 0, 6) == 'export') && !$REV && $INFO['exists'])
    {
        // get TOC from metadata, render if neccessary
        $meta = p_get_metadata($ID, false, METADATA_RENDER_USING_CACHE);
        if (isset($meta['internal']['toc']))
        {
            $tocok = $meta['internal']['toc'];
        }
        else
        {
            $tocok = true;
        }
        $toc = isset($meta['description']['tableofcontents'])
            ? $meta['description']['tableofcontents']
            : null;
        if (!$tocok || !is_array($toc) || !$conf['tocminheads'] || count($toc) < $conf['tocminheads'])
        {
            $toc = array();
        }
    }
    elseif ($ACT == 'admin')
    {
        // try to load admin plugin TOC FIXME: duplicates code from tpl_admin
        $plugin = null;
        $class  = $INPUT->str('page');
        if (!empty($class))
        {
            $pluginlist = plugin_list('admin');
            if (in_array($class, $pluginlist))
            {
                // attempt to load the plugin
                /** @var $plugin DokuWiki_Admin_Plugin */
                $plugin = plugin_load('admin', $class);
            }
        }
        if (($plugin !== null) && (!$plugin->forAdminOnly() || $INFO['isadmin']))
        {
            $toc = $plugin->getTOC();
            $TOC = $toc; // avoid later rebuild
        }
    }

    trigger_event('TPL_TOC_RENDER', $toc, null, false);
    $html = vkb_html_TOC($toc);
    if ($return)
    {
        return $html;
    }
    echo $html;

    return '';
}

function vkb_html_TOC($toc)
{
    if (!count($toc))
    {
        return '';
    }
    global $lang;

    $return = '<div class="panel-navbar-toc">';

    $return .= '<div class="panel-body navbar-toc">';
    $return .= preg_replace('/(<li[^>]*class="[^"]*)/', '$1 active', vkb_html_buildlist($toc, 'nav', 'html_list_toc', 'html_li_default', true), 1);
    $return .= '</div>';

    $return .= '</div>';

    return $return;
}

function vkb_html_buildlist($data, $class, $func, $lifunc = 'html_li_default', $forcewrapper = false)
{
    if (count($data) === 0)
    {
        return '';
    }

    $start_level = $data[0]['level'];
    $level       = $start_level;
    $ret         = '';
    $open        = 0;

    foreach ($data as $item)
    {

        if ($item['level'] > $level)
        {
            //open new list
            for ($i = 0; $i < ($item['level'] - $level); $i++)
            {
                if ($i)
                {
                    $ret .= "<li class=\"clear\">";
                }
                $ret .= "\n<ul class=\"$class\">\n";
                $open++;
            }
            $level = $item['level'];
        }
        elseif ($item['level'] < $level)
        {
            //close last item
            $ret .= "</li>\n";
            while ($level > $item['level'] && $open > 0)
            {
                //close higher lists
                $ret .= "</ul>\n</li>\n";
                $level--;
                $open--;
            }
        }
        elseif ($ret !== '')
        {
            //close previous item
            $ret .= "</li>\n";
        }

        //print item
        $ret .= call_user_func($lifunc, $item);

        $ret .= call_user_func($func, $item);
    }

    //close remaining items and lists
    $ret .= "</li>\n";
    while ($open-- > 0)
    {
        $ret .= "</ul></li>\n";
    }

    if ($forcewrapper || $start_level < 2)
    {
        // Trigger building a wrapper ul if the first level is
        // 0 (we have a root object) or 1 (just the root content)
        $ret = "\n<ul class=\"$class\">\n" . $ret . "</ul>\n";
    }

    return $ret;
}

function vkb_html_msgarea()
{
    global $MSG;
    global $MSG_shown;
    $MSG_shown = true;
    if (!isset($MSG))
    {
        return;
    }

    $shown = array();
    foreach ((array) $MSG as $msg)
    {
        $hash = md5($msg['msg']);
        if (isset($shown[$hash]))
        {
            continue;
        } // skip double messages
        if (info_msg_allowed($msg))
        {
            $class = 'alert-info';
            switch ($msg['lvl'])
            {
                case 'success':
                    $class = 'alert-success';
                    break;
                case 'error':
                    $class = 'alert-danger';
                    break;
            }
            echo '<div class="alert ' . $class . '">';
            echo $msg['msg'];
            echo '</div>';
        }
        $shown[$hash] = 1;
    }

    unset($GLOBALS['MSG']);
}

function vkb_tpl_actionlink($type, $pre = '', $suf = '', $inner = '', $return = false)
{
    $result = preg_replace('/class="[^"]*"/', '', tpl_actionlink($type, $pre, $suf, $inner, true));
    if ($result === false || empty($result))
    {
        return false;
    }
    if ($return)
    {
        return $result;
    }
    echo $result;

    return true;
}

function vkb_html_secedit($text, $show = true)
{
    global $INFO;

    $regexp = '#<!-- EDIT(\d+) ([A-Z_]+) (?:"([^"]*)" )?\[(\d+-\d*)\] -->#';

    if (!$INFO['writable'] || !$show || $INFO['rev'])
    {
        return preg_replace($regexp, '', $text);
    }

    return preg_replace_callback($regexp, 'vkb_html_secedit_button', $text);
}

function vkb_html_secedit_button($matches)
{
    $data = array(
        'secid'  => $matches[1],
        'target' => strtolower($matches[2]),
        'range'  => $matches[count($matches) - 1]
    );
    if (count($matches) === 5)
    {
        $data['name'] = $matches[3];
    }

    return trigger_event('HTML_SECEDIT_BUTTON', $data,
                         'vkb_html_secedit_get_button');
}

function vkb_html_secedit_get_button($data)
{
    global $ID;
    global $INFO;

    if (!isset($data['name']) || $data['name'] === '')
    {
        return '';
    }

    $name = $data['name'];
    unset($data['name']);

    $secid = $data['secid'];
    unset($data['secid']);

    return "<div class='secedit editbutton_" . $data['target'] .
           " editbutton_" . $secid . "'><hr />" .
           preg_replace('/(<input[^<]*)class="[^"]*"/', '$1class="btn btn-sm btn-danger"', html_btn('secedit', $ID, '',
                                                                                                    array_merge(array(
                                                                                                                    'do'      => 'edit',
                                                                                                                    'rev'     => $INFO['lastmod'],
                                                                                                                    'summary' => '[' . $name . '] '
                                                                                                                ), $data),
                                                                                                    'post', $name) . '</div>');
}

function vkb_html_edit_form($param)
{
    global $TEXT;

    if ($param['target'] !== 'section')
    {
        msg('No editor for edit target ' . hsc($param['target']) . ' found.', -1);
    }

    $attr = array('tabindex' => '1', 'class' => 'form-control');
    if (!$param['wr'])
    {
        $attr['readonly'] = 'readonly';
    }

    echo '<textarea id="wiki__text" class="edit form-control" name="wikitext"></textarea>';
//    $param['form']->addElement(form_makeWikiText($TEXT, $attr));
}