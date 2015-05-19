<?php
/* Copyright (C) 2007-2010 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) ---Put here your own copyright and developer email---
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 *   	\file       dev/Warehousepositionss/Warehousepositions_page.php
 *		\ingroup    mymodule othermodule1 othermodule2
 *		\brief      This file is an example of a php page
 *					Initialy built by build_class_from_table on 2015-03-02 08:22
 */

//if (! defined('NOREQUIREUSER'))  define('NOREQUIREUSER','1');
//if (! defined('NOREQUIREDB'))    define('NOREQUIREDB','1');
//if (! defined('NOREQUIRESOC'))   define('NOREQUIRESOC','1');
//if (! defined('NOREQUIRETRAN'))  define('NOREQUIRETRAN','1');
//if (! defined('NOCSRFCHECK'))    define('NOCSRFCHECK','1');			// Do not check anti CSRF attack test
//if (! defined('NOSTYLECHECK'))   define('NOSTYLECHECK','1');			// Do not check style html tag into posted data
//if (! defined('NOTOKENRENEWAL')) define('NOTOKENRENEWAL','1');		// Do not check anti POST attack test
//if (! defined('NOREQUIREMENU'))  define('NOREQUIREMENU','1');			// If there is no need to load and show top and left menu
//if (! defined('NOREQUIREHTML'))  define('NOREQUIREHTML','1');			// If we don't need to load the html.form.class.php
//if (! defined('NOREQUIREAJAX'))  define('NOREQUIREAJAX','1');
//if (! defined("NOLOGIN"))        define("NOLOGIN",'1');				// If this page is public (can be called outside logged session)

// Change this following line to use the correct relative path (../, ../../, etc)
$res=0;
if (! $res && file_exists("../main.inc.php")) $res=@include '../main.inc.php';					// to work if your module directory is into dolibarr root htdocs directory
if (! $res && file_exists("../../main.inc.php")) $res=@include '../../main.inc.php';			// to work if your module directory is into a subdir of root htdocs directory
if (! $res && file_exists("../../../htdocs/main.inc.php")) $res=@include '../../../htdocs/main.inc.php';     // Used on dev env only
if (! $res && file_exists("../../../../htdocs/main.inc.php")) $res=@include '../../../../htdocs/main.inc.php';   // Used on dev env only
if (! $res) die("Include of main fails");
// Change this following line to use the correct relative path from htdocs
dol_include_once(DOL_DOCUMENT_ROOT."/appeloffre/class/Appeloffre.class.php");
require_once(DOL_DOCUMENT_ROOT."/appeloffre/class/Appeloffre.class.php");


// Load traductions files requiredby by page
$langs->load("companies");
$langs->load("other");

// Get parameters
$id			= GETPOST('id','int');
$action		= GETPOST('action','alpha');
$myparam	= GETPOST('myparam','alpha');


if (!isset($action))
    $action =  'list';
// Protection if external user
if ($user->societe_id > 0)
{
	//accessforbidden();
}



// Start of transaction
$db->begin();


// Examples for manipulating class skeleton_class
//require_once(DOL_DOCUMENT_ROOT."/warehousepositions/warehousepositions.class.php");
//$myobject=new Warehousepositions($db);
//
//dol_syslog($script_file." CREATE", LOG_DEBUG);
//
//$myobject->title='position1';
//$myobject->description='Description position1';
//$myobject->entity=1;
//$id=$myobject->create($user);
//if ($id < 0) { $error++; dol_print_error($db,$myobject->error); }
////else print "Object created with id=".$id."\n";


/*******************************************************************
* ACTIONS
*
* Put here all code to do according to value of "action" parameter
********************************************************************/

if ($action == 'add')
{
	$object=new Appeloffre($db);
	$object->title=$_POST["title"];
	$object->description=$_POST["description"];
	$result=$object->create($user);
	if ($result > 0)
	{
		// Creation OK
            $db->commit();
	}
	{
		// Creation KO
		$mesg=$object->error;
	}
}





/***************************************************
* VIEW
*
* Put here all code to build page
****************************************************/

llxHeader('',"Liste appels d'offre",'');

$form=new Form($db);
$id =  $_GET['id'];
//$product = new Product($db);
//$result=$product->fetch($id);
//
//$head=product_prepare_head($product, $user);
//$titre=$langs->trans("CardProduct".$product->type);
//$picto=($product->type==1?'service':'product');
//dol_fiche_head($head, 'wareposition', $titre, 0, $picto);


// Put here content of your page

// Example 1 : Adding jquery code
print '<script type="text/javascript" language="javascript">
jQuery(document).ready(function() {
	function init_myfunc()
	{
		jQuery("#myid").removeAttr(\'disabled\');
		jQuery("#myid").attr(\'disabled\',\'disabled\');
	}
	init_myfunc();
	jQuery("#mybutton").click(function() {
		init_needroot();
	});
});
</script>';


// Example 2 : Adding links to objects
// The class must extends CommonObject class to have this method available
//$somethingshown=$object->showLinkedObjectBlock();


// Example 3 : List of data
if ($action == 'list' || true)
{
    $sql = "SELECT";
    $sql.= " t.*";   
    $sql.= " FROM ".MAIN_DB_PREFIX."appeloffre as t";
//    $sql.= " WHERE field3 = 'xxx'";
//    $sql.= " ORDER BY field1 ASC";

    
//    print $sql;
    print '<table class="noborder">'."\n";
    print '<tr class="liste_titre">';
    print '<th class="liste_titre">Réf</th>';
    print '<th class="liste_titre">Date</th>';
    print '<th class="liste_titre">État</th>';
    print '<th class="liste_titre">Montant</th>';
    print '<th class="liste_titre">Adjudicataire</th>';
    print '<th class="liste_titre">Architecte(s)</th>';
    print '<th class="liste_titre">BET(s)</th>';
    print '</tr>';

    dol_syslog($script_file." sql=".$sql, LOG_DEBUG);
    $resql=$db->query($sql);
    if ($resql)
    {
        $num = $db->num_rows($resql);
        $i = 0;
        if ($num)
        {
            while ($i < $num)
            {
                $obj = $db->fetch_object($resql);
                if ($obj)
                {
                    // You can use here results
                    print '<tr>';
                    
                    print '<td>';
                    print sprintf('%08d', $obj->id);
//                    print $obj->id;   
                    print '</td>';
                    
                    print '<td>';
                    print $obj->date_create;
                    print '</td>';
                    
                    print '<td>';
                    print "Ouvert";
                    print '</td>';
                    print '<td>';
                    print $obj->amount_attributed;
                    print '</td>';
                    
                    print '<td>';
                    print "Aucun";
                    print '</td>';
                    print '<td>';
                    
                    print "Aucun";
                    print '</td>';
                    
                    print '<td>';
                    print "Aucun";
                    print '</td>';
                    
                 
                    
                    
                    print '</tr>';
                }
                $i++;
            }
        }
    }
    else
    {
        $error++;
        dol_print_error($db);
    }

    print '</table>'."\n";
}



// End of page
llxFooter();
$db->close();
