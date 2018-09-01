<?php
/*
Plugin Name: Perso About
Version: 2.8.b
Description: Add bloc perso on about page
Plugin URI: http://piwigo.org/ext/extension_view.php?eid=480
Author: ddtddt
Author URI: http://temmii.com/piwigo/
*/
// +-----------------------------------------------------------------------+
// | Perso About plugin for piwigo                                         |
// +-----------------------------------------------------------------------+
// | Copyright(C) 2010 - 2016 ddtddt             http://temmii.com/piwigo/ |
// +-----------------------------------------------------------------------+
// | This program is free software; you can redistribute it and/or modify  |
// | it under the terms of the GNU General Public License as published by  |
// | the Free Software Foundation                                          |
// |                                                                       |
// | This program is distributed in the hope that it will be useful, but   |
// | WITHOUT ANY WARRANTY; without even the implied warranty of            |
// | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU      |
// | General Public License for more details.                              |
// |                                                                       |
// | You should have received a copy of the GNU General Public License     |
// | along with this program; if not, write to the Free Software           |
// | Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA 02111-1307, |
// | USA.                                                                  |
// +-----------------------------------------------------------------------+

if (!defined('PHPWG_ROOT_PATH')) die('Hacking attempt!');

define('PPA_DIR' , basename(dirname(__FILE__)));
define('PPA_PATH' , PHPWG_PLUGINS_PATH . PPA_DIR . '/');
define('PPA_ADMIN',get_root_url().'admin.php?page=plugin-'.PPA_DIR);

add_event_handler('loading_lang', 'perso_about_loading_lang');	  
function perso_about_loading_lang(){
  load_language('plugin.lang', PPA_PATH);
}

add_event_handler('get_admin_plugin_menu_links', 'PA_admin_menu');
function PA_admin_menu($menu){
  array_push($menu, array(
	'NAME' => l10n('ppa_h2'),
    'URL' => get_admin_plugin_menu_link(PPA_PATH . 'admin.php')));
  return $menu;
}

if (script_basename() == 'about'){
  add_event_handler('loc_end_page_header', 'ppa');
}

function ppa(){
  global $template, $conf;
  $template->set_prefilter('about', 'ppaT');
  $PAED = pwg_db_fetch_assoc(pwg_query("SELECT state FROM " . PLUGINS_TABLE . " WHERE id = 'ExtendedDescription';"));
  if($PAED['state'] == 'active') add_event_handler('AP_render_content', 'get_user_language_desc');
  $pat=trigger_change('AP_render_content', $conf['persoAbout']);
  if (!empty($pat)){
	$template->assign('PERSO_ABOUT', $pat);
  }
}

function ppaT($content, &$smarty){
  $search = '{$ABOUT_MESSAGE}';
  $replacement = '<div id="persoabout">{$PERSO_ABOUT}</div>
  {$ABOUT_MESSAGE}';
  return str_replace($search, $replacement, $content);
}
?>