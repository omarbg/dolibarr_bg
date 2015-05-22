<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require '../main.inc.php';

$term = GETPOST('term');
$socid = GETPOST('socid','int');

$query ="SELECT * FROM `llx_contact` WHERE `nom` LIKE '%$term%' OR `prenom` LIKE '%$term%'";
$resql=$db->query($query);
$return  = array();


$num = $db->num_rows($resql);
        $i = 0;
        if ($num)
        {
            while ($i < $num)
            {
                $obj = $db->fetch_object($resql);
                if ($obj)
                {
                    $rs  = array();
                    $rs['id']= $obj->id;
                    $rs['value']= $obj->nom." ".$obj->prenom;
                    
                    $html =  '<table><tr>';
                    
                    $html .=  '<td>';
                    
                    $html .= "<a href='card.php?id=$obj->rowid'>".$obj->nom." ".$obj->prenom."</a>";
//                    print $obj->id;   
                    $html .= '</td>';
            
                    $html .= '<td>';
                    $html .= $obj->email;
                    $html .= '</td>';
                    
                    $html .= '<td>';
                    $html .= $obj->telephone1;
                    $html .= '</td>';
                    $html .= '<td>';                    
                    $html .= $obj->telephone2;
                    $html .= '</td>';
                    $html .= '<td>';                    
                    $html .= $obj->methode_contact;
                    $html .= '</td>';
                    $html .= '<td>';
                    $html .= '<a href="'.DOL_URL_ROOT.'/societe/contacts.php?socid='.$socid.'&action=addc&id_contact='.$obj->rowid.'">Ajouter</a>';
                    $html .= '</td>';                  
                    $html .= '</tr></table>';
                    
                    
                    
                    $rs['html']= $html;
                    $return[] = $rs; 
                }
                $i++;
            
        }
        
        
        
                }
                
                echo json_encode($return);





