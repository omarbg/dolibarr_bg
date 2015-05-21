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
require_once DOL_DOCUMENT_ROOT.'/appeloffre/lib/appeloffre.lib.php';

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
$action = GETPOST('action');
$id_contact = GETPOST('id_contact');
$soc = GETPOST('soc');

if (isset($action)){
    if ($action==='addc'){
        //check if already exist
        $query = "SELECT count(*) as nbr FROM `".MAIN_DB_PREFIX."contact_tiers` WHERE `id_contact`=$id_contact AND `id_tiers`=$socid";
        $count = 0;
   
        if ($res =$db->query($query)){
                  $obj = $db->fetch_object($res);
                  $count =  $obj->nbr;
        }
        if (!$count){
        $sqla =  "INSERT INTO `".MAIN_DB_PREFIX."contact_tiers`(`id_contact`, `id_tiers`) VALUES ('$id_contact','$socid')";        
        
        if ($db->query($sqla)){
            setEventMessage('Contact ajouté avec succes');
        }  else {
                setEventMessage("probleme d'ajout du Contact",'errors');
        }}  else {
        setEventMessage(" Contact déja exist",'errors');
            
}
    }else if( $action==='removec'){
        
        $sqla = "DELETE FROM `".MAIN_DB_PREFIX."contact_tiers` WHERE `id_contact` =$id_contact and `id_tiers`=$socid";
      
        if ($db->query($sqla)){
            setEventMessage('Contact Enlevé avec succes');
        }  else {
                setEventMessage("probleme de suppression du Contact",'errors');
        }        
        
    }
}

$soc_contacts  = soc_contacts($socid);

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
                    
                    if (in_array($obj->rowid, $soc_contacts))                    
                    {print '<a href="'.$_SERVER['PHP_SELF'].'?socid='.$socid.'&action=removec&id_contact='.$obj->rowid.'">Enlever</a>';}
                    else
                    {print '<a href="'.$_SERVER['PHP_SELF'].'?socid='.$socid.'&action=addc&id_contact='.$obj->rowid.'">Ajouter</a>';}
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
?>
<script type="text/javascript">
$(function(){
    $( "#search_contact" ).autocomplete({
      source: "search_contacts.php",
      minLength: 2,
      select: function( event, ui ) {
        log( ui.item ?
          "Selected: " + ui.item.value + " aka " + ui.item.id :
          "Nothing selected, input was " + this.value );
      }
    });
})

</script> 


<?php
llxFooter();

$db->close();
