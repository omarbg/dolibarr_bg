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
 * Actions
 */



// Add a product or service
if ($action == 'add') {
    $error = 0;

    if (!GETPOST('title')) {
        setEventMessage($langs->trans('ErrorFieldRequired', $langs->transnoentities('Label')), 'errors');
        $action = "create";
        $error++;
    }

    if (!$error) {
        $object->ref = getNextValueRef();
        $object->label = GETPOST('title');
        $object->description = GETPOST('description');
        $object->date_create = date('d-m-Y H:i:s');
        $object->date_butoir = GETPOST('date_butoir');
        $object->budget_est = GETPOST('budget_est');
        $object->buy_step = GETPOST('buy_step');
//            $object->amount_attributed= GETPOST('amount_attributed');
        $object->segmentation = GETPOST('segmentation');
        $object->secteur_geo = GETPOST('secteur_geo');

        if (!$error) {
            $id = $object->create($user);
        }

        if ($id > 0) {
            header("Location: list.php");
            exit;
        } else {
            if (count($object->errors))
                setEventMessage($object->errors, 'errors');
            else
                setEventMessage($langs->trans($object->error), 'errors');
            $action = "create";
        }
    }
}

// Update a product or service
if ($action == 'update') {
    if (GETPOST('cancel')) {
        $action = '';
    } else {
        if ($object->id > 0) {
            $object->oldcopy = dol_clone($object);

            $object->label = GETPOST('title');
            $object->description = GETPOST('description');
            $object->date_create = date('d-m-Y H:i:s');
            $object->date_butoir = GETPOST('date_butoir');
            $object->budget_est = GETPOST('budget_est');
            $object->buy_step = GETPOST('buy_step');
//                $object->amount_attributed= GETPOST('amount_attributed');
            $object->segmentation = GETPOST('segmentation');
            $object->secteur_geo = GETPOST('secteur_geo');

            if (!$error) {
                if ($object->update($object->id, $user) > 0) {
                    $action = 'view';
                } else {
                    if (count($object->errors))
                        setEventMessage($object->errors, 'errors');
                    else
                        setEventMessage($langs->trans($object->error), 'errors');
                    $action = 'edit';
                }
            }
            else {
                if (count($object->errors))
                    setEventMessage($object->errors, 'errors');
                else
                    setEventMessage($langs->trans("ErrorProductBadRefOrLabel"), 'errors');
                $action = 'edit';
            }
        }
    }
}
if ($action == 'confirm_cloture') {
    if (GETPOST('cancel')) {
        $action = '';
    } else {
        if ($object->id > 0) {
            if ((int)GETPOST('etat_offre'))
            {
            
            $object->amount_attributed= GETPOST('amount_attributed');
            
            $socid = $user->societe_id;
            
            $object->Adjudicataire =  $socid;
            
            if (!$error) {
                if ($object->update($object->id, $user) > 0) {
                    $action = 'view';
                } else {
                    if (count($object->errors))
                        setEventMessage($object->errors, 'errors');
                    else
                        setEventMessage($langs->trans($object->error), 'errors');
                    $action = 'edit';
                }
            }
            else {
                if (count($object->errors))
                    setEventMessage($object->errors, 'errors');
                else
                    setEventMessage($langs->trans("Mauvaise id d'offre"), 'errors');
                $action = 'edit';
            }}
        }
    }
}

// Action clone object
if ($action == 'confirm_clone' && $confirm != 'yes') {
    $action = '';
}


// Delete a product
if ($action == 'confirm_delete' && $confirm != 'yes') {
    $action = '';
}
if ($action == 'confirm_delete' && $confirm == 'yes') {
    $result = $object->delete($object->id);


    if ($result > 0) {
        header('Location: ' . DOL_URL_ROOT . '/appeloffre/list.php');
        exit;
    } else {
        setEventMessage($langs->trans($object->error), 'errors');
        $reload = 0;
        $action = '';
    }
}


if (GETPOST("cancel") == $langs->trans("Cancel")) {
    $action = '';
    header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $object->id);
    exit;
}


/*
 * View
 */

$helpurl = '';


$title = $langs->trans('Nouveau Offre');
if (!isset($action)) {
    $titre = $langs->trans("Fiche Offre");
}

llxHeader('', $title, $helpurl);

if ($action === 'create') {
    $form = new Form($db);

    // -----------------------------------------
    // When used in standard mode
    // -----------------------------------------
    //WYSIWYG Editor
    require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';

    print '<form action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
    print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
    print '<input type="hidden" name="action" value="add">';
    print_fiche_titre($title);

    print '<table class="border" width="100%">';

    print'<tr><td><span class="fieldrequired">Libellé</span></td><td><input size="30" type="text" name="title" value=""></td></tr>';

    print '<tr><td valign="top">' . $langs->trans("Description") . '</td><td colspan="3">';

    $doleditor = new DolEditor('description', GETPOST('description'), '', 160, 'description', '', false, true, $conf->global->FCKEDITOR_ENABLE_PRODUCTDESC, 4, 80);
    $doleditor->Create();

    print "</td></tr>";

    // Public URL
    print '<tr><td valign="top">' . $langs->trans("Budget Estimé") . '</td><td colspan="3">';
    print '<input type="text" name="budget_est" size="25" value="' . GETPOST('budget_est') . '">';
    print '</td></tr>';
    print '<tr><td valign="top">' . $langs->trans("secteur géographique") . '</td><td colspan="3">';
    print '<input type="text" name="secteur_geo" size="25" value="' . GETPOST('secteur_geo') . '">';
    print '</td></tr>';
    print '<tr><td valign="top">' . $langs->trans("Segmentation") . '</td><td colspan="3">';
    print '<input type="text" name="segmentation" size="25" value="' . GETPOST('segmentation') . '">';
    print '</td></tr>';

    print '<tr><td valign="top">' . $langs->trans("Etape de paiment") . '</td><td colspan="3">';
    print '<input type="text" name="buy_step" size="25" value="' . GETPOST('buy_step') . '">';
    print '</td></tr>';
//    print '<tr><td valign="top">' . $langs->trans("Montant attribué") . '</td><td colspan="3">';
//    print '<input type="text" name="amount_attributed" size="25" value="' . GETPOST('amount_attributed') . '">';
//    print '</td></tr>';
    // Date start
    print '<tr><td>' . $langs->trans("Date butoir") . '</td><td>';
    print $form->select_date(($date_butoir ? $date_butoir : ''), 'date_butoir');
    print '</td></tr>';


    print '</table>';

    print '<br>';
    //}

    print '<center><input type="submit" class="button" value="' . $langs->trans("Create") . '"></center>';

    print '</form>';
} elseif ($action === 'edit') {
    $form = new Form($db);

    // -----------------------------------------
    // When used in standard mode
    // -----------------------------------------
    //WYSIWYG Editor
    require_once DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php';
    $id = GETPOST('id');
    $offre = new Appeloffre($db);
    $offre->fetch($id);


    print '<form action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
    print '<input type="hidden" name="token" value="' . $_SESSION['newtoken'] . '">';
    print '<input type="hidden" name="action" value="update">';
    print '<input type="hidden" name="id" value="' . $id . '">';
    print_fiche_titre($title);

    print '<table class="border" width="100%">';

    print'<tr><td><span class="fieldrequired">Libellé</span></td><td><input size="30" type="text" name="title" value="' . $offre->label . '"></td></tr>';

    print '<tr><td valign="top">' . $langs->trans("Description") . '</td><td colspan="3">';

    $doleditor = new DolEditor('description', $offre->description, '', 160, 'description', '', false, true, $conf->global->FCKEDITOR_ENABLE_PRODUCTDESC, 4, 80);
    $doleditor->Create();

    print "</td></tr>";

    // Public URL
    print '<tr><td valign="top">' . $langs->trans("Budget Estimé") . '</td><td colspan="3">';
    print '<input type="text" name="budget_est" size="25" value="' . $offre->budget_est . '">';
    print '</td></tr>';
    print '<tr><td valign="top">' . $langs->trans("secteur géographique") . '</td><td colspan="3">';
    print '<input type="text" name="secteur_geo" size="25" value="' . $offre->secteur_geo . '">';
    print '</td></tr>';
    print '<tr><td valign="top">' . $langs->trans("Segmentation") . '</td><td colspan="3">';
    print '<input type="text" name="segmentation" size="25" value="' . $offre->segmentation . '">';
    print '</td></tr>';

    print '<tr><td valign="top">' . $langs->trans("Etape de paiment") . '</td><td colspan="3">';
    print '<input type="text" name="buy_step" size="25" value="' . $offre->buy_step . '">';
    print '</td></tr>';
//    print '<tr><td valign="top">' . $langs->trans("Montant attribué") . '</td><td colspan="3">';
//    print '<input type="text" name="amount_attributed" size="25" value="' . GETPOST('amount_attributed') . '">';
//    print '</td></tr>';
    // Date start
    print '<tr><td>' . $langs->trans("Date butoir") . '</td><td>';
    print $form->select_date(($date_butoir ? $date_butoir : ''), 'date_butoir');
    print '</td></tr>';


    print '</table>';

    print '<br>';
    //}

    print '<center><input type="submit" class="button" value="' . $langs->trans("Modify") . '"></center>';

    print '</form>';
} else {
//mode visual du fiche offre 

    $appel_offre = new Appeloffre($db);
    $appel_offre->fetch($id);


    $head = offre_prepare_head($appel_offre, $user);
    $titre = $langs->trans("Fiche Offre");
    $picto = 'project';
    dol_fiche_head($head, 'card', $titre, 0, $picto);
    if ($action == 'delete' || ($conf->use_javascript_ajax && empty($conf->dol_use_jmobile))) {
        $form = new Form($db);

        print $form->formconfirm($_SERVER["PHP_SELF"] . "?id=" . $object->id, $langs->trans("Supprimer Offre"), $langs->trans("Voulez vous faire la suppression?"), "confirm_delete", '', 0, "action-delete");
    }

    
  //cloture  
// if (($action == 'cloture' && (empty($conf->use_javascript_ajax) || ! empty($conf->dol_use_jmobile)))		// Output when action = clone if jmobile or no js
//	|| (! empty($conf->use_javascript_ajax) && empty($conf->dol_use_jmobile)))							// Always output when not jmobile nor js
//{
//    print $form->formconfirm($_SERVER["PHP_SELF"].'?id='.$object->id,$langs->trans('Cloture produit'),$langs->trans('ConfirmCloneProduct',$object->ref),'confirm_clone',$formquestionclone,'yes','action-cloture',250,600);
//}
    
    $etats =array();
    $etats[0]= "perdu";
    $etats[1]= "gagné";
   $formquestion=array(
	'text' => $langs->trans("Etat de l'offre"),
        array('type' => 'select', 'name' => 'etat_offre', 'values' => $etats)
        ,array('type' => 'text', 'name' => 'amount_attributed','label' => $langs->trans("montant attribué"), 'value' => '', 'size'=>24),

       );
   
   
// Clone confirmation
if (($action == 'clone' && (empty($conf->use_javascript_ajax) || ! empty($conf->dol_use_jmobile)))		// Output when action = clone if jmobile or no js
	|| (! empty($conf->use_javascript_ajax) && empty($conf->dol_use_jmobile)))							// Always output when not jmobile nor js
{
    print $form->formconfirm($_SERVER["PHP_SELF"].'?id='.$object->id,$langs->trans('Cloturer Offre'),$langs->trans("Êtes-vous sûr de vouloir cloturer l'offre <b>$object->ref</b> ? "),'confirm_cloture',$formquestion,'yes','action-clone',250,600);
}
    
    
    // En mode visu
    print '<table class="border" width="100%"><tr>';

    // Ref
    print '<td width="15%">' . $langs->trans("Ref") . '</td><td>';
    print $appel_offre->ref;
    print '</td>';
    print '</tr>';
    // Label
    print '<tr><td>' . $langs->trans("Label") . '</td><td colspan="2">' . $appel_offre->label . '</td>';

    print '<tr><td>' . $langs->trans("Description") . '</td><td colspan="2">' . $appel_offre->description . '</td>';

    print '<tr><td>' . $langs->trans("Date de sortie") . '</td><td colspan="2">' . $appel_offre->date_sortie . '</td>';

    print '<tr><td>' . $langs->trans("Date de création") . '</td><td colspan="2">' . $appel_offre->date_create . '</td>';

    print '<tr><td>' . $langs->trans("Date butoir") . '</td><td colspan="2">' . $appel_offre->date_butoir . '</td>';

    print '<tr><td>' . $langs->trans("Estimation budgétaire") . '</td><td colspan="2">' . $appel_offre->budget_est . '</td>';

    print '<tr><td>' . $langs->trans("secteur géographique") . '</td><td colspan="2">' . $appel_offre->secteur_geo . '</td>';

    print '<tr><td>' . $langs->trans("Segmentation") . '</td><td colspan="2">' . $appel_offre->segmentation . '</td>';

    print '<tr><td>' . $langs->trans("Etape de paiment") . '</td><td colspan="2">' . $appel_offre->buy_step . '</td>';
    if ($appel_offre->amount_attributed)
        print '<tr><td>' . $langs->trans("Montant attribué") . '</td><td colspan="2">' . $appel_offre->amount_attributed . '</td>';
if ($appel_offre->Adjudicataire)
    {
    $soc = new Societe($db);
    $soc->fetch($appel_offre->Adjudicataire);
    print '<tr><td>' . $langs->trans("Adjudicataire") . '</td><td colspan="2">' . $soc->getNomUrl(1). '</td>';
    }        
            
//            print $soc->name;
            
    print '<tr><td>' . $langs->trans("Contact référent") . '</td><td colspan="2">' . $appel_offre->contacts. '</td>';


    $utl = new User($db);
    $utl->fetch($appel_offre->owner);


    print '<tr><td>' . $langs->trans("Utilisateur") . '</td><td colspan="2">' . $utl->getNomUrl() . '</td>';


    print "</table>\n";

    dol_fiche_end();

    if ($user->rights->offre->create) {
        print '<div class="inline-block divButAction"><a class="butAction" href="' . $_SERVER["PHP_SELF"] . '?id=' . $object->id . '&amp;action=edit">' . $langs->trans("Modify") . '</a></div>' . "\n";
    }

    if ($user->rights->offre->delete) {
        print '<div class="inline-block divButAction"><span id="action-delete" class="butActionDelete">' . $langs->trans('Delete') . '</span></div>' . "\n";
    }
    if ($user->rights->offre->cloture) {
        print '<div class="inline-block divButAction"><span id="action-clone" class="butActionDelete">' . $langs->trans('Cloturer') . '</span></div>' . "\n";
    }
}


llxFooter();
$db->close();
