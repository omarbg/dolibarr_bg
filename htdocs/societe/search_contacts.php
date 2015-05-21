<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require '../main.inc.php';

$term = GETPOST('term');

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
                    $return[] = $rs; 
                }
            
        }
        
        
        
                }
                
                echo json_encode($return);





