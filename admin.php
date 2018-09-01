<?php
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
global $template, $conf, $user;
include_once(PHPWG_ROOT_PATH .'admin/include/tabsheet.class.php');
load_language('plugin.lang', PPA_PATH);
$my_base_url = PHPWG_ROOT_PATH.'admin.php?page=plugin-';

// +-----------------------------------------------------------------------+
// | Check Access and exit when user status is not ok                      |
// +-----------------------------------------------------------------------+
check_status(ACCESS_ADMINISTRATOR);

//-------------------------------------------------------- sections definitions
if (!is_webmaster()){
  array_push($page['errors'], l10n('This section is reserved for the webmaster'));
} else{

// Gestion des onglets
if (!isset($_GET['tab']))
    $page['tab'] = 'gest';
else
    $page['tab'] = $_GET['tab'];

$tabsheet = new tabsheet();
  $tabsheet->add('gest', l10n('ppa_tab_gest'), PPA_ADMIN . '-gest');
  $tabsheet->add('help', l10n('ppa_tab_help'), PPA_ADMIN . '-help');
$tabsheet->select($page['tab']);
$tabsheet->assign();

// Onglet gest
switch ($page['tab'])
{
  case 'gest':
$template->assign(
  'gestA',
    array('PPABASE' => $conf['persoAbout'],));
$PAED = pwg_db_fetch_assoc(pwg_query("SELECT state FROM " . PLUGINS_TABLE . " WHERE id = 'ExtendedDescription';"));
  if($PAED['state'] == 'active'){
	$template->assign('useED',1);
  }else{
    $template->assign('useED',0);
  }
if (isset($_POST['submitppa'])){
  conf_update_param('persoAbout', $_POST['perso_about']);
  $template->assign(
    'gestA',
    array('PPABASE' => stripslashes($_POST['perso_about']),));
}
  break;

// Tab help
  case 'help':
$template->assign(
  'gestB',
	array('meta'=>l10n('null'),));
  break;
	
} 

$template->set_filenames(array('plugin_admin_content' => dirname(__FILE__) . '/admin.tpl')); 
$template->assign_var_from_handle('ADMIN_CONTENT', 'plugin_admin_content');
}
?>