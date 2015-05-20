<?php

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/appeloffre/class/appeloffre.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/genericobject.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';
require_once DOL_DOCUMENT_ROOT . '/appeloffre/lib/appeloffre.lib.php';

$langs->load("appeloffre");


$mesg = '';
$error = 0;
$errors = array();
$_error = 0;

$id = GETPOST('id', 'int');
$ref = GETPOST('ref', 'alpha');
$type = GETPOST('type', 'int');
$action = (GETPOST('action', 'alpha') ? GETPOST('action', 'alpha') : 'view');
$confirm = GETPOST('confirm', 'alpha');
$socid = GETPOST('socid', 'int');
if (!empty($user->societe_id))
    $socid = $user->societe_id;

$object = new Appeloffre($db);
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

if ($id > 0) {
    $object = new Appeloffre($db);
    $object->fetch($id);
}




/*
 * View
 */

$helpurl = '';


$title = $langs->trans('Fiche Offre');

llxHeader('', $title, $helpurl);

//mode visual du fiche offre 

$appel_offre = new Appeloffre($db);
$appel_offre->fetch($id);


$head = offre_prepare_head($appel_offre, $user);
$titre = $langs->trans("Offre Card");
$picto = 'project';
dol_fiche_head($head, 'contacts', $titre, 0, $picto);


 $sql = "SELECT";
    $sql.= " t.*";   
    $sql.= " FROM ".MAIN_DB_PREFIX."contact as t";
    $sql.= " WHERE rowid in (1,2,8,9,10)";

    
//    print $sql;
    print '<table class="noborder">'."\n";
    print '<tr class="liste_titre">';
    print '<th class="liste_titre">Nom </th>';
    print '<th class="liste_titre">Adresse</th>';
    print '<th class="liste_titre">Email</th>';
    print '<th class="liste_titre">Tél 1</th>';
    print '<th class="liste_titre">Tél 2</th>';    
    print '<th class="liste_titre">Methode de contact</th>';    
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
                    
                    print "<a href='contacts/card.php?id=$obj->rowid'>".$obj->nom." ".$obj->prenom."</a>";
//                    print $obj->id;   
                    print '</td>';
                    
                    print '<td>';
                    print substr($obj->adresse,0,100).'...';
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
                    
                    print '</tr>';
                }
                $i++;
            }
        }
    }
print '</table>';

dol_fiche_end();


llxFooter();
$db->close();
