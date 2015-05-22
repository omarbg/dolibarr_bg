<?php

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/appeloffre/class/appeloffre.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/genericobject.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT . '/appeloffre/lib/appeloffre.lib.php';
require_once DOL_DOCUMENT_ROOT . '/appeloffre/class/tcontact.class.php';
require_once DOL_DOCUMENT_ROOT.'/societe/class/societe.class.php';


$langs->load("appeloffre");


$mesg = '';
$error = 0;
$errors = array();
$_error = 0;

$id = GETPOST('id', 'int');

$object = new Tcontact($db);
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

if ($id > 0) {
    $object = new Tcontact($db);
    $object->fetch($id);
}




/*
 * View
 */

$helpurl = '';


$title = $langs->trans('Fiche Offre');

llxHeader('', $title, $helpurl);

//mode visual du fiche offre 

$head=contact_prepare_head($object, $user);
$titre = $langs->trans("Offre Card");
$picto = 'project';
dol_fiche_head($head, 'tiers', $titre, 0, $picto);

$tiers  = contact_socs($id);

if (count($tiers)){
    $guids_tiers = implode(',', $tiers);
    
 $sql = "SELECT";
    $sql.= " s.*";   
    $sql.= " FROM ".MAIN_DB_PREFIX."societe as s";
    $sql.= " WHERE rowid in ($guids_tiers)";

    
//    print $sql;
    print '<table class="noborder">'."\n";
    print '<tr class="liste_titre">';
    print '<th class="liste_titre">Nom </th>';
    print '<th class="liste_titre">Etat</th>';    
    print '</tr>';

    dol_syslog($script_file." sql=".$sql, LOG_DEBUG);
    $resql=$db->query($sql);
    $thirdparty_static = new Societe($db);

    if ($resql)
    {
        $num = $db->num_rows($resql);
        $i = 0;
        if ($num)
        {
            $var=True;

        while ($i < $num)
        {
            $objp = $db->fetch_object($resql);

            $var=!$var;
            print "<tr ".$bc[$var].">";
            // Name
            print '<td class="nowrap">';
            $soc = new Societe($db);
            $soc->fetch($objp->rowid);
            
            
//            print $soc->name;
            print $soc->getNomUrl(1);
            print "</td>\n";
    
            print '<td align="right" class="nowrap">';
            print $soc->getLibStatut(3);
            print "</td>";
            print "</tr>\n";
            $i++;
        }

        }
    }
print '</table>';}  else {
print "<p>Aucun Tiers pour ce Contact</p>"    ;    
}

dol_fiche_end();


llxFooter();
$db->close();
