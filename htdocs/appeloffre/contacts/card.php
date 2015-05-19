<?php

require '../../main.inc.php';
require_once DOL_DOCUMENT_ROOT . '/appeloffre/class/contact.class.php';
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
$action = (GETPOST('action', 'alpha') ? GETPOST('action', 'alpha') : 'view');
$confirm = GETPOST('confirm', 'alpha');


$object = new Contact($db);
$extrafields = new ExtraFields($db);

// fetch optionals attributes and labels
$extralabels = $extrafields->fetch_name_optionals_label($object->table_element);

if ($id > 0) {
    $object = new Contact($db);
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
            
            $object->nom        = GETPOST('nom');
            $object->prenom     = GETPOST('prenom');
            $object->telephone1 = GETPOST('telephone1');
            $object->telephone2 = date('telephone2');
            $object->address    = GETPOST('address');
            $object->email      = GETPOST('email');
     
            
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

                 $object->nom        = GETPOST('nom');
                 $object->prenom     = GETPOST('prenom');
                 $object->telephone1 = GETPOST('telephone1');
                 $object->telephone2 = date('telephone2');
                 $object->address    = GETPOST('address');
                 $object->email      = GETPOST('email');

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


if (GETPOST("cancel") == $langs->trans("Cancel")) {
    $action = '';
    header("Location: " . $_SERVER["PHP_SELF"] . "?id=" . $object->id);
    exit;
}


/*
 * View
 */

$helpurl = '';


$title = $langs->trans('Nouveau Contact');

llxHeader('', $title, $helpurl);

if ($action ==='create')  
{
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
    
    print'<tr><td><span class="fieldrequired">Nom</span></td><td><input size="30" type="text" name="nom" value=""></td></tr>'; 

    print'<tr><td><span class="fieldrequired">Prenom</span></td><td><input size="30" type="text" name="prenom" value=""></td></tr>'; 
    
    print'<tr><td><span >Adresse</span></td><td>';
     $doleditor = new DolEditor('address', GETPOST('address'), '', 160, 'address', '', false, true, $conf->global->FCKEDITOR_ENABLE_PRODUCTDESC, 4, 80);
    $doleditor->Create();
print '</td></tr>';
    
    print'<tr><td><span >Email</span></td><td><input size="30" type="text" name="email" value=""></td></tr>'; 
    
    print'<tr><td><span >Tél 1</span></td><td><input size="30" type="text" name="telephone1" value=""></td></tr>'; 
    
    print'<tr><td><span >Tél 2</span></td><td><input size="30" type="text" name="telephone2" value=""></td></tr>'; 

    print '</table>';

    print '<br>';
    //}

    print '<center><input type="submit" class="button" value="' . $langs->trans("Create") . '"></center>';

    print '</form>';

}  else {
//mode visual du fiche offre 
    
$contact = new Contact($db);
$contact->fetch($id);


            $head=offre_prepare_head($contact, $user);
            $titre=$langs->trans("Offre Card");
            $picto='product';
            dol_fiche_head($head, 'card', $titre, 0, $picto);

          
            // En mode visu
            print '<table class="border" width="100%"><tr>';

         
            print '<tr><td>'.$langs->trans("Nom").'</td><td colspan="2">'.$contact->nom.'</td>';
            
            print '<tr><td>'.$langs->trans("Prenom").'</td><td colspan="2">'.$contact->prenom.'</td>';
            
            print '<tr><td>'.$langs->trans("Adresse").'</td><td colspan="2">'.$contact->address.'</td>';

            print '<tr><td>'.$langs->trans("Email").'</td><td colspan="2">'.$contact->email.'</td>';
            
            print '<tr><td>'.$langs->trans("Tél 1").'</td><td colspan="2">'.$contact->telephone1.'</td>';

            print '<tr><td>'.$langs->trans("Tél 2").'</td><td colspan="2">'.$contact->telephone2.'</td>';
            
            print "</table>\n";

            dol_fiche_end();
    
    
    
    
    
    
}





llxFooter();
$db->close();
