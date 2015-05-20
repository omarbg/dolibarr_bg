<?php
/* Copyright (C) 2004-2009	Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012	Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2010		Juanjo Menent		<jmenent@2byte.es>
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
 *      \file       htdocs/societe/info.php
 *      \ingroup    societe
 *      \brief      Page des informations d'une societe
 */

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/functions2.lib.php';
require_once DOL_DOCUMENT_ROOT.'/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';

$langs->load("companies");
$langs->load("other");
if (! empty($conf->notification->enabled)) $langs->load("mails");

// Security check
$socid = GETPOST('socid','int');
if ($user->societe_id) $socid=$user->societe_id;
$result = restrictedArea($user, 'societe', $socid, '&societe');

// Initialize technical object to manage hooks of thirdparties. Note that conf->hooks_modules contains array array
$hookmanager->initHooks(array('infothirdparty'));



/*
 *	Actions
 */

$parameters=array('id'=>$socid);
$reshook=$hookmanager->executeHooks('doActions',$parameters,$object,$action);    // Note that $action and $object may have been modified by some hooks
if ($reshook < 0) setEventMessages($hookmanager->error, $hookmanager->errors, 'errors');



/*
 *	View
 */

$help_url='EN:Module_Third_Parties|FR:Module_Tiers|ES:Empresas';
llxHeader('',$langs->trans("ThirdParty"),$help_url);

$object = new Societe($db);
$object->fetch($socid);


/*
 * Affichage onglets
 */
$head = societe_prepare_head($object);

dol_fiche_head($head, 'contacts', $langs->trans("ThirdParty"),0,'company');


    $sql = "SELECT";
    $sql.= " t.*";   
    $sql.= " FROM ".MAIN_DB_PREFIX."contact as t";
//
//    print $sql;
    print '<table class="noborder">'."\n";
    print '<tr class="liste_titre">';
    print '<th class="liste_titre">Nom </th>';
    print '<th class="liste_titre">Email</th>';
    print '<th class="liste_titre">Tél 1</th>';
    print '<th class="liste_titre">Tél 2</th>';    
    print '<th class="liste_titre">Methode de contact</th>';    
    print '<th class="liste_titre"></th>';    
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
                    
                    print "<a href='card.php?id=$obj->rowid'>".$obj->nom." ".$obj->prenom."</a>";
//                    print $obj->id;   
                    print '</td>';
            
                    print '<td>';
                    print $obj->email;
                    print '</td>';
                    
                    print '<td>';
                    print $obj->telephone1;
                    print '</td>';
                    print '<td>';                    
                    print $obj->telephone2;
                    print '</td>';
                    print '<td>';                    
                    print $obj->methode_contact;
                    print '</td>';
                    print '<td>';
                    print '<a href="'.$_SERVER['PHP_SELF'].'?action=remove&contact_id='.$obj->rowid.'">Enlever</a>';
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
    print "<p>";
    print "</p>";
    print "<p>";
    print "<input type='text' id='search_contact' />";
    
    
    print "</p>";
    
    
    
    
    
    

dol_fiche_end();


llxFooter();

$db->close();
