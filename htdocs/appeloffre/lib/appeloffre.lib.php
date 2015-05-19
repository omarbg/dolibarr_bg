<?php
/* <one line to give the program's name and a brief idea of what it does.>
 * Copyright (C) <year>  <name of author>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *	\file		lib/appeloffre.lib.php
 *	\ingroup	appeloffre
 *	\brief		This file is an example module library
 *				Put some comments here
 */

function appeloffreAdminPrepareHead()
{
	global $langs, $conf;

	$langs->load("appeloffre@appeloffre");

	$h = 0;
	$head = array();

	$head[$h][0] = dol_buildpath("/appeloffre/admin/admin_appeloffre.php", 1);
	$head[$h][1] = $langs->trans("Settings");
	$head[$h][2] = 'settings';
	$h++;
	$head[$h][0] = dol_buildpath("/appeloffre/admin/about.php", 1);
	$head[$h][1] = $langs->trans("About");
	$head[$h][2] = 'about';
	$h++;

	// Show more tabs from modules
	// Entries must be declared in modules descriptor with line
	//$this->tabs = array(
	//	'entity:+tabname:Title:@appeloffre:/appeloffre/mypage.php?id=__ID__'
	//); // to add new tab
	//$this->tabs = array(
	//	'entity:-tabname:Title:@appeloffre:/appeloffre/mypage.php?id=__ID__'
	//); // to remove a tab
	complete_head_from_modules($conf, $langs, $object, $head, $h, 'appeloffre');

	return $head;
}
function getNextValueRef()
	{
		global $db,$conf;

                $prefix  = "OFR";
		// D'abord on recupere la valeur max
		$sql = "SELECT MAX(id) as max";	// This is standard SQL
		$sql.= " FROM ".MAIN_DB_PREFIX."appeloffre";
		

		$resql=$db->query($sql);
    		if ($resql)
		{
			$obj = $db->fetch_object($resql);
			if ($obj) $max = intval($obj->max);
			else $max=0;
		}
		else
		{
			return -1;
		}

	
    		$date=time();	// This is invoice date (not creation date)
    		$yymm = strftime("%y%m",$date);

    		if ($max >= (pow(10, 4) - 1)) $num=$max+1;	// If counter > 9999, we do not format on 4 chars, we take number as it is
    		else $num = sprintf("%04s",$max+1);
                return $prefix.$yymm."-".$num;
		
	}
        
        
/**
 * Prepare array with list of tabs
 *
 * @param   Object	$object		Object related to tabs
 * @param	User	$user		Object user
 * @return  array				Array of tabs to show
 */
function offre_prepare_head($object, $user)
{
	global $langs, $conf;
	$langs->load("appeloffre");

	$h = 0;
	$head = array();

	$head[$h][0] = DOL_URL_ROOT."/appeloffre/card.php?id=".$object->id;
	$head[$h][1] = $langs->trans("Card");
	$head[$h][2] = 'card';
	$h++;

	$head[$h][0] = DOL_URL_ROOT."/product/price.php?id=".$object->id;
	$head[$h][1] = $langs->trans("CustomerPrices");
	$head[$h][2] = 'price';
	$h++;

	return $head;
}