<?php
/* Copyright (C) 2007-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
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
 *  \file       dev/skeletons/appeloffre.class.php
 *  \ingroup    mymodule othermodule1 othermodule2
 *  \brief      This file is an example for a CRUD class file (Create/Read/Update/Delete)
 *				Initialy built by build_class_from_table on 2015-05-13 00:17
 */

// Put here all includes required by your class file
require_once(DOL_DOCUMENT_ROOT."/core/class/commonobject.class.php");
//require_once(DOL_DOCUMENT_ROOT."/societe/class/societe.class.php");
//require_once(DOL_DOCUMENT_ROOT."/product/class/product.class.php");


/**
 *	Put here description of your class
 */
class Appeloffre extends CommonObject
{
	var $db;							//!< To store db handler
	var $error;							//!< To return error code (or message)
	var $errors=array();				//!< To return several error codes (or messages)
	var $element='appeloffre';			//!< Id that identify managed objects
	var $table_element='appeloffre';		//!< Name of table without prefix where object is stored

    var $id;
    
	var $ref;
	var $label;
	var $fk_tiers;
	var $visible;
	var $description;
	var $date_sortie;
	var $date_create;
	var $date_butoir;
	var $budget_est;
	var $secteur_geo;
	var $segmentation;
	var $buy_step;
	var $status;
	var $Adjudicataire;
	var $amount_attributed;
	var $fk_contact;
	var $owner;
	var $contacts;

    


    /**
     *  Constructor
     *
     *  @param	DoliDb		$db      Database handler
     */
    function __construct($db)
    {
        $this->db = $db;
        return 1;
    }


    /**
     *  Create object into database
     *
     *  @param	User	$user        User that creates
     *  @param  int		$notrigger   0=launch triggers after, 1=disable triggers
     *  @return int      		   	 <0 if KO, Id of created object if OK
     */
    function create($user, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->ref)) $this->ref=trim($this->ref);
		if (isset($this->label)) $this->label=trim($this->label);
		if (isset($this->fk_tiers)) $this->fk_tiers=trim($this->fk_tiers);
		if (isset($this->visible)) $this->visible=trim($this->visible);
		if (isset($this->description)) $this->description=trim($this->description);
		if (isset($this->date_sortie)) $this->date_sortie=trim($this->date_sortie);
		if (isset($this->date_create)) $this->date_create=trim($this->date_create);
		if (isset($this->date_butoir)) $this->date_butoir=trim($this->date_butoir);
		if (isset($this->budget_est)) $this->budget_est=trim($this->budget_est);
		if (isset($this->secteur_geo)) $this->secteur_geo=trim($this->secteur_geo);
		if (isset($this->segmentation)) $this->segmentation=trim($this->segmentation);
		if (isset($this->buy_step)) $this->buy_step=trim($this->buy_step);
		if (isset($this->status)) $this->status=trim($this->status);
		if (isset($this->Adjudicataire)) $this->Adjudicataire=trim($this->Adjudicataire);
		if (isset($this->amount_attributed)) $this->amount_attributed=trim($this->amount_attributed);
		if (isset($this->fk_contact)) $this->fk_contact=trim($this->fk_contact);
		if (isset($this->owner)) $this->owner=trim($this->owner);
		if (isset($this->contacts)) $this->contacts=trim($this->contacts);

        

		// Check parameters
		// Put here code to add control on parameters values

        // Insert request
		$sql = "INSERT INTO ".MAIN_DB_PREFIX."appeloffre(";
		
		$sql.= "ref,";
		$sql.= "label,";
		$sql.= "fk_tiers,";
		$sql.= "visible,";
		$sql.= "description,";
		$sql.= "date_sortie,";
		$sql.= "date_create,";
		$sql.= "date_butoir,";
		$sql.= "budget_est,";
		$sql.= "secteur_geo,";
		$sql.= "segmentation,";
		$sql.= "buy_step,";
		$sql.= "status,";
		$sql.= "Adjudicataire,";
		$sql.= "amount_attributed,";
		$sql.= "fk_contact,";
		$sql.= "owner,";
		$sql.= "contacts";

		
        $sql.= ") VALUES (";
        
		$sql.= " ".(! isset($this->ref)?'NULL':"'".$this->db->escape($this->ref)."'").",";
		$sql.= " ".(! isset($this->label)?'NULL':"'".$this->db->escape($this->label)."'").",";
		$sql.= " ".(! isset($this->fk_tiers)?'NULL':"'".$this->fk_tiers."'").",";
		$sql.= " ".(! isset($this->visible)?'NULL':"'".$this->visible."'").",";
		$sql.= " ".(! isset($this->description)?'NULL':"'".$this->db->escape($this->description)."'").",";
		$sql.= " ".(! isset($this->date_sortie)?'NULL':"'".$this->db->escape($this->date_sortie)."'").",";
		$sql.= " ".(! isset($this->date_create)?'NULL':"'".$this->db->escape($this->date_create)."'").",";
		$sql.= " ".(! isset($this->date_butoir)?'NULL':"'".$this->db->escape($this->date_butoir)."'").",";
		$sql.= " ".(! isset($this->budget_est)?'NULL':"'".$this->budget_est."'").",";
		$sql.= " ".(! isset($this->secteur_geo)?'NULL':"'".$this->db->escape($this->secteur_geo)."'").",";
		$sql.= " ".(! isset($this->segmentation)?'NULL':"'".$this->db->escape($this->segmentation)."'").",";
		$sql.= " ".(! isset($this->buy_step)?'NULL':"'".$this->buy_step."'").",";
		$sql.= " ".(! isset($this->status)?'NULL':"'".$this->status."'").",";
		$sql.= " ".(! isset($this->Adjudicataire)?'NULL':"'".$this->Adjudicataire."'").",";
		$sql.= " ".(! isset($this->amount_attributed)?'NULL':"'".$this->amount_attributed."'").",";
		$sql.= " ".(! isset($this->fk_contact)?'NULL':"'".$this->fk_contact."'").",";
		$sql.= " ".(! isset($this->owner)?'NULL':"'".$this->owner."'").",";
		$sql.= " ".(! isset($this->contacts)?'NULL':"'".$this->db->escape($this->contacts)."'")."";

        
		$sql.= ")";

		$this->db->begin();

	   	dol_syslog(get_class($this)."::create sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
        {
            $this->id = $this->db->last_insert_id(MAIN_DB_PREFIX."appeloffre");

			if (! $notrigger)
			{
	            // Uncomment this and change MYOBJECT to your own tag if you
	            // want this action calls a trigger.

	            //// Call triggers
	            //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
	            //$interface=new Interfaces($this->db);
	            //$result=$interface->run_triggers('MYOBJECT_CREATE',$this,$user,$langs,$conf);
	            //if ($result < 0) { $error++; $this->errors=$interface->errors; }
	            //// End call triggers
			}
        }

        // Commit or rollback
        if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::create ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
            return $this->id;
		}
    }


    /**
     *  Load object in memory from the database
     *
     *  @param	int		$id    Id object
     *  @return int          	<0 if KO, >0 if OK
     */
    function fetch($id)
    {
    	global $langs;
        $sql = "SELECT";
		$sql.= " t.rowid,";
		
		$sql.= " t.id,";
		$sql.= " t.ref,";
		$sql.= " t.label,";
		$sql.= " t.fk_tiers,";
		$sql.= " t.visible,";
		$sql.= " t.description,";
		$sql.= " t.date_sortie,";
		$sql.= " t.date_create,";
		$sql.= " t.date_butoir,";
		$sql.= " t.budget_est,";
		$sql.= " t.secteur_geo,";
		$sql.= " t.segmentation,";
		$sql.= " t.buy_step,";
		$sql.= " t.status,";
		$sql.= " t.Adjudicataire,";
		$sql.= " t.amount_attributed,";
		$sql.= " t.fk_contact,";
		$sql.= " t.owner,";
		$sql.= " t.contacts";

		
        $sql.= " FROM ".MAIN_DB_PREFIX."appeloffre as t";
        $sql.= " WHERE t.rowid = ".$id;

    	dol_syslog(get_class($this)."::fetch sql=".$sql, LOG_DEBUG);
        $resql=$this->db->query($sql);
        if ($resql)
        {
            if ($this->db->num_rows($resql))
            {
                $obj = $this->db->fetch_object($resql);

                $this->id    = $obj->rowid;
                
				$this->ref = $obj->ref;
				$this->label = $obj->label;
				$this->fk_tiers = $obj->fk_tiers;
				$this->visible = $obj->visible;
				$this->description = $obj->description;
				$this->date_sortie = $obj->date_sortie;
				$this->date_create = $obj->date_create;
				$this->date_butoir = $obj->date_butoir;
				$this->budget_est = $obj->budget_est;
				$this->secteur_geo = $obj->secteur_geo;
				$this->segmentation = $obj->segmentation;
				$this->buy_step = $obj->buy_step;
				$this->status = $obj->status;
				$this->Adjudicataire = $obj->Adjudicataire;
				$this->amount_attributed = $obj->amount_attributed;
				$this->fk_contact = $obj->fk_contact;
				$this->owner = $obj->owner;
				$this->contacts = $obj->contacts;

                
            }
            $this->db->free($resql);

            return 1;
        }
        else
        {
      	    $this->error="Error ".$this->db->lasterror();
            dol_syslog(get_class($this)."::fetch ".$this->error, LOG_ERR);
            return -1;
        }
    }


    /**
     *  Update object into database
     *
     *  @param	User	$user        User that modifies
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
     *  @return int     		   	 <0 if KO, >0 if OK
     */
    function update($user=0, $notrigger=0)
    {
    	global $conf, $langs;
		$error=0;

		// Clean parameters
        
		if (isset($this->ref)) $this->ref=trim($this->ref);
		if (isset($this->label)) $this->label=trim($this->label);
		if (isset($this->fk_tiers)) $this->fk_tiers=trim($this->fk_tiers);
		if (isset($this->visible)) $this->visible=trim($this->visible);
		if (isset($this->description)) $this->description=trim($this->description);
		if (isset($this->date_sortie)) $this->date_sortie=trim($this->date_sortie);
		if (isset($this->date_create)) $this->date_create=trim($this->date_create);
		if (isset($this->date_butoir)) $this->date_butoir=trim($this->date_butoir);
		if (isset($this->budget_est)) $this->budget_est=trim($this->budget_est);
		if (isset($this->secteur_geo)) $this->secteur_geo=trim($this->secteur_geo);
		if (isset($this->segmentation)) $this->segmentation=trim($this->segmentation);
		if (isset($this->buy_step)) $this->buy_step=trim($this->buy_step);
		if (isset($this->status)) $this->status=trim($this->status);
		if (isset($this->Adjudicataire)) $this->Adjudicataire=trim($this->Adjudicataire);
		if (isset($this->amount_attributed)) $this->amount_attributed=trim($this->amount_attributed);
		if (isset($this->fk_contact)) $this->fk_contact=trim($this->fk_contact);
		if (isset($this->owner)) $this->owner=trim($this->owner);
		if (isset($this->contacts)) $this->contacts=trim($this->contacts);

        

		// Check parameters
		// Put here code to add a control on parameters values

        // Update request
        $sql = "UPDATE ".MAIN_DB_PREFIX."appeloffre SET";
        
		$sql.= " ref=".(isset($this->ref)?"'".$this->db->escape($this->ref)."'":"null").",";
		$sql.= " label=".(isset($this->label)?"'".$this->db->escape($this->label)."'":"null").",";
		$sql.= " fk_tiers=".(isset($this->fk_tiers)?$this->fk_tiers:"null").",";
		$sql.= " visible=".(isset($this->visible)?$this->visible:"null").",";
		$sql.= " description=".(isset($this->description)?"'".$this->db->escape($this->description)."'":"null").",";
		$sql.= " date_sortie=".(isset($this->date_sortie)?"'".$this->db->escape($this->date_sortie)."'":"null").",";
		$sql.= " date_create=".(isset($this->date_create)?"'".$this->db->escape($this->date_create)."'":"null").",";
		$sql.= " date_butoir=".(isset($this->date_butoir)?"'".$this->db->escape($this->date_butoir)."'":"null").",";
		$sql.= " budget_est=".(isset($this->budget_est)?$this->budget_est:"null").",";
		$sql.= " secteur_geo=".(isset($this->secteur_geo)?"'".$this->db->escape($this->secteur_geo)."'":"null").",";
		$sql.= " segmentation=".(isset($this->segmentation)?"'".$this->db->escape($this->segmentation)."'":"null").",";
		$sql.= " buy_step=".(isset($this->buy_step)?$this->buy_step:"null").",";
		$sql.= " status=".(isset($this->status)?$this->status:"null").",";
		$sql.= " Adjudicataire=".(isset($this->Adjudicataire)?$this->Adjudicataire:"null").",";
		$sql.= " amount_attributed=".(isset($this->amount_attributed)?$this->amount_attributed:"null").",";
		$sql.= " fk_contact=".(isset($this->fk_contact)?$this->fk_contact:"null").",";
		$sql.= " owner=".(isset($this->owner)?$this->owner:"null").",";
		$sql.= " contacts=".(isset($this->contacts)?"'".$this->db->escape($this->contacts)."'":"null")."";

        
        $sql.= " WHERE rowid=".$this->id;

		$this->db->begin();

		dol_syslog(get_class($this)."::update sql=".$sql, LOG_DEBUG);
        $resql = $this->db->query($sql);
    	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }

		if (! $error)
		{
			if (! $notrigger)
			{
	            // Uncomment this and change MYOBJECT to your own tag if you
	            // want this action calls a trigger.

	            //// Call triggers
	            //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
	            //$interface=new Interfaces($this->db);
	            //$result=$interface->run_triggers('MYOBJECT_MODIFY',$this,$user,$langs,$conf);
	            //if ($result < 0) { $error++; $this->errors=$interface->errors; }
	            //// End call triggers
	    	}
		}

        // Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::update ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
			return 1;
		}
    }


 	/**
	 *  Delete object in database
	 *
     *	@param  User	$user        User that deletes
     *  @param  int		$notrigger	 0=launch triggers after, 1=disable triggers
	 *  @return	int					 <0 if KO, >0 if OK
	 */
	function delete($user, $notrigger=0)
	{
		global $conf, $langs;
		$error=0;

		$this->db->begin();

		if (! $error)
		{
			if (! $notrigger)
			{
				// Uncomment this and change MYOBJECT to your own tag if you
		        // want this action calls a trigger.

		        //// Call triggers
		        //include_once DOL_DOCUMENT_ROOT . '/core/class/interfaces.class.php';
		        //$interface=new Interfaces($this->db);
		        //$result=$interface->run_triggers('MYOBJECT_DELETE',$this,$user,$langs,$conf);
		        //if ($result < 0) { $error++; $this->errors=$interface->errors; }
		        //// End call triggers
			}
		}

		if (! $error)
		{
    		$sql = "DELETE FROM ".MAIN_DB_PREFIX."appeloffre";
    		$sql.= " WHERE rowid=".$this->id;

    		dol_syslog(get_class($this)."::delete sql=".$sql);
    		$resql = $this->db->query($sql);
        	if (! $resql) { $error++; $this->errors[]="Error ".$this->db->lasterror(); }
		}

        // Commit or rollback
		if ($error)
		{
			foreach($this->errors as $errmsg)
			{
	            dol_syslog(get_class($this)."::delete ".$errmsg, LOG_ERR);
	            $this->error.=($this->error?', '.$errmsg:$errmsg);
			}
			$this->db->rollback();
			return -1*$error;
		}
		else
		{
			$this->db->commit();
			return 1;
		}
	}



	/**
	 *	Load an object from its id and create a new one in database
	 *
	 *	@param	int		$fromid     Id of object to clone
	 * 	@return	int					New id of clone
	 */
	function createFromClone($fromid)
	{
		global $user,$langs;

		$error=0;

		$object=new Appeloffre($this->db);

		$this->db->begin();

		// Load source object
		$object->fetch($fromid);
		$object->id=0;
		$object->statut=0;

		// Clear fields
		// ...

		// Create clone
		$result=$object->create($user);

		// Other options
		if ($result < 0)
		{
			$this->error=$object->error;
			$error++;
		}

		if (! $error)
		{


		}

		// End
		if (! $error)
		{
			$this->db->commit();
			return $object->id;
		}
		else
		{
			$this->db->rollback();
			return -1;
		}
	}


	/**
	 *	Initialise object with example values
	 *	Id must be 0 if object instance is a specimen
	 *
	 *	@return	void
	 */
	function initAsSpecimen()
	{
		$this->id=0;
		
		$this->ref='';
		$this->label='';
		$this->fk_tiers='';
		$this->visible='';
		$this->description='';
		$this->date_sortie='';
		$this->date_create='';
		$this->date_butoir='';
		$this->budget_est='';
		$this->secteur_geo='';
		$this->segmentation='';
		$this->buy_step='';
		$this->status='';
		$this->Adjudicataire='';
		$this->amount_attributed='';
		$this->fk_contact='';
		$this->owner='';
		$this->contacts='';

		
	}

}
