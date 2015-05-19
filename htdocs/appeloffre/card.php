<?php

require '../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/appeloffre/class/appeloffre.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/extrafields.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/genericobject.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/lib/company.lib.php';

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


// Security check
$fieldvalue = (!empty($id) ? $id : (!empty($ref) ? $ref : ''));
$fieldtype = (!empty($ref) ? 'ref' : 'rowid');
$result = restrictedArea($user, 'produit|service', $fieldvalue, 'product&product', '', '', $fieldtype, $objcanvas);

// Initialize technical object to manage hooks of thirdparties. Note that conf->hooks_modules contains array array
//$hookmanager->initHooks(array('productcard', 'globalcard'));



/*
 * Actions
 */


if (empty($reshook)) {
    // Type
    if ($action == 'setfk_product_type' && $user->rights->produit->creer) {
        $result = $object->setValueFrom('fk_product_type', GETPOST('fk_product_type'));
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $object->id);
        exit;
    }



    // Add a product or service
    if ($action == 'add') {
        $error = 0;

        if (!GETPOST('title')) {
            setEventMessage($langs->trans('ErrorFieldRequired', $langs->transnoentities('Label')), 'errors');
            $action = "create";
            $error++;
        }

        if (!$error) {
            $object->ref = $ref;
            $object->label = GETPOST('title');
            $object->description = GETPOST('description');
            $object->date_create= date('d-m-Y H:i:s');
            $object->date_butoir= GETPOST('date_butoir');
            $object->budget_est= GETPOST('budget_est');
            $object->buy_step= GETPOST('buy_step');
            $object->amount_attributed= GETPOST('amount_attributed');
            $object->segmentation= GETPOST('segmentation');
            $object->secteur_geo= GETPOST('secteur_geo');
            
            if (!$error) {
                $id = $object->create($user);
            }

            if ($id > 0) {
                header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $id);
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

                 $object->ref = $ref;
                $object->label = GETPOST('title');
                $object->description = GETPOST('description');
                $object->date_create= date('d-m-Y H:i:s');
                $object->date_butoir= GETPOST('date_butoir');
                $object->budget_est= GETPOST('budget_est');
                $object->buy_step= GETPOST('buy_step');
                $object->amount_attributed= GETPOST('amount_attributed');
                $object->segmentation= GETPOST('segmentation');
                $object->secteur_geo= GETPOST('secteur_geo');

                if (!$error && $object->check()) {
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

    // Action clone object
    if ($action == 'confirm_clone' && $confirm != 'yes') {
        $action = '';
    }
    if ($action == 'confirm_clone' && $confirm == 'yes' ) {
  
            $db->begin();

            $originalId = $id;
            if ($object->id > 0) {
                $object->ref = GETPOST('clone_ref');
                $object->status = 0;
                $object->status_buy = 0;
                $object->id = null;

                if ($object->check()) {
                    $id = $object->create($user);
                    if ($id > 0) {
                        if (GETPOST('clone_composition')) {
                            $result = $object->clone_associations($originalId, $id);

                            if ($result < 1) {
                                $db->rollback();
                                setEventMessage($langs->trans('ErrorProductClone'), 'errors');
                                header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $originalId);
                                exit;
                            }
                        }

                        // $object->clone_fournisseurs($originalId, $id);

                        $db->commit();
                        $db->close();

                        header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $id);
                        exit;
                    } else {
                        $id = $originalId;

                        if ($object->error == 'ErrorProductAlreadyExists') {
                            $db->rollback();

                            $_error++;
                            $action = "";

                            $mesg = $langs->trans("ErrorProductAlreadyExists", $object->ref);
                            $mesg.=' <a href="' . $_SERVER["PHP_SELF"] . '?ref=' . $object->ref . '">' . $langs->trans("ShowCardHere") . '</a>.';
                            setEventMessage($mesg, 'errors');
                            $object->fetch($id);
                        } else {
                            $db->rollback();
                            if (count($object->errors)) {
                                setEventMessage($object->errors, 'errors');
                                dol_print_error($db, $object->errors);
                            } else {
                                setEventMessage($langs->trans($object->error), 'errors');
                                dol_print_error($db, $object->error);
                            }
                        }
                    }
                }
            } else {
                $db->rollback();
                dol_print_error($db, $object->error);
            }
        
    }

    // Delete a product
    if ($action == 'confirm_delete' && $confirm != 'yes') {
        $action = '';
    }
    if ($action == 'confirm_delete' && $confirm == 'yes') {
            $result = $object->delete($object->id);
        

        if ($result > 0) {
            header('Location: ' . DOL_URL_ROOT . '/appeloffre/list.php?type=' . $object->type . '&delprod=' . urlencode($object->ref));
            exit;
        } else {
            setEventMessage($langs->trans($object->error), 'errors');
            $reload = 0;
            $action = '';
        }
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

llxHeader('', $title, $helpurl);

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
    print '<tr><td valign="top">' . $langs->trans("Montant attribué") . '</td><td colspan="3">';
    print '<input type="text" name="amount_attributed" size="25" value="' . GETPOST('amount_attributed') . '">';
    print '</td></tr>';

   
     // Date start
    print '<tr><td>'.$langs->trans("Date butoir").'</td><td>';
    print $form->select_date(($date_butoir?$date_butoir:''),'date_butoir');
    print '</td></tr>';


    print '</table>';

    print '<br>';
    //}

    print '<center><input type="submit" class="button" value="' . $langs->trans("Create") . '"></center>';

    print '</form>';



    /*
     * Product card
     */





llxFooter();
$db->close();
