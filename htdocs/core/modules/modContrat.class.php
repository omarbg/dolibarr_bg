<?php
/* Copyright (C) 2005      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2014 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2010 Regis Houssin        <regis.houssin@capnetworks.com>
 * Copyright (C) 2011      Juanjo Menent	    <jmenent@2byte.es>
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
 *	\defgroup   contrat     Module contract
 *	\brief      Module pour gerer la tenue de contrat de services
 *	\file       htdocs/core/modules/modContrat.class.php
 *	\ingroup    contrat
 *	\brief      Fichier de description et activation du module Contrat
 */

include_once DOL_DOCUMENT_ROOT .'/core/modules/DolibarrModules.class.php';


/**
 *	Classe de description et activation du module Contrat
 */
class modContrat extends DolibarrModules
{

	/**
	 *   Constructor. Define names, constants, directories, boxes, permissions
	 *
	 *   @param      DoliDB		$db      Database handler
	 */
	function __construct($db)
	{
		global $conf;
		
		$this->db = $db;
		$this->numero = 54;

		$this->family = "crm";
		// Module label (no space allowed), used if translation string 'ModuleXXXName' not found (where XXX is value of numeric property 'numero' of module)
		$this->name = preg_replace('/^mod/i','',get_class($this));
		$this->description = "Gestion des contrats de services";

		// Possible values for version are: 'development', 'experimental', 'dolibarr' or version
		$this->version = 'dolibarr';

		$this->const_name = 'MAIN_MODULE_'.strtoupper($this->name);
		$this->special = 0;
		$this->picto='contract';

		// Data directories to create when module is enabled
		$this->dirs = array("/contract/temp");

		// Dependances
		$this->depends = array("modSociete");
		$this->requiredby = array();

		// Config pages
		$this->config_page_url = array("contract.php");

		// Constantes
		$this->const = array();
		$this->const[0][0] = "CONTRACT_ADDON";
		$this->const[0][1] = "chaine";
		$this->const[0][2] = "mod_contract_serpis";
		$this->const[0][3] = 'Nom du gestionnaire de numerotation des contrats';
		$this->const[0][4] = 0;

		// Boxes
		$this->boxes = array(
			0=>array('file'=>'box_contracts.php','enabledbydefaulton'=>'Home'),
			1=>array('file'=>'box_services_expired.php','enabledbydefaulton'=>'Home')
		);

		// Permissions
		$this->rights = array();
		$this->rights_class = 'contrat';

		$this->rights[1][0] = 161;
		$this->rights[1][1] = 'Lire les contrats';
		$this->rights[1][2] = 'r';
		$this->rights[1][3] = 1;
		$this->rights[1][4] = 'lire';

		$this->rights[2][0] = 162;
		$this->rights[2][1] = 'Creer / modifier les contrats';
		$this->rights[2][2] = 'w';
		$this->rights[2][3] = 0;
		$this->rights[2][4] = 'creer';

		$this->rights[3][0] = 163;
		$this->rights[3][1] = 'Activer un service d\'un contrat';
		$this->rights[3][2] = 'w';
		$this->rights[3][3] = 0;
		$this->rights[3][4] = 'activer';

		$this->rights[4][0] = 164;
		$this->rights[4][1] = 'Desactiver un service d\'un contrat';
		$this->rights[4][2] = 'w';
		$this->rights[4][3] = 0;
		$this->rights[4][4] = 'desactiver';

		$this->rights[5][0] = 165;
		$this->rights[5][1] = 'Supprimer un contrat';
		$this->rights[5][2] = 'd';
		$this->rights[5][3] = 0;
		$this->rights[5][4] = 'supprimer';

		// Exports
		//--------
		$r=1;

		$this->export_code[$r]=$this->rights_class.'_'.$r;
		$this->export_label[$r]='ContractAndServices';	// Translation key (used only if key ExportDataset_xxx_z not found)
		$this->export_icon[$r]='contract';
		$this->export_permission[$r]=array(array("contrat","export"));
		$this->export_fields_array[$r]=array('s.rowid'=>"IdCompany",'s.nom'=>'CompanyName','s.address'=>'Address','s.zip'=>'Zip','s.town'=>'Town','c.code'=>'CountryCode',
		's.phone'=>'Phone','s.siren'=>'ProfId1','s.siret'=>'ProfId2','s.ape'=>'ProfId3','s.idprof4'=>'ProfId4','s.code_compta'=>'CustomerAccountancyCode',
		's.code_compta_fournisseur'=>'SupplierAccountancyCode','s.tva_intra'=>'VATIntra',
		'co.rowid'=>"contractId",'co.ref'=>"contactRef",'co.datec'=>"contractDateCreation",'co.date_contrat'=>"DateContract",'co.mise_en_service'=>"DateMiseService",
		'co.fin_validite'=>"EndValidity",'co.date_cloture'=>"Cloture",'co.note_private'=>"NotePrivate",'co.note_public'=>"NotePublic",
		'cod.rowid'=>'LineId','cod.label'=>"LineLabel",'cod.description'=>"LineDescription",'cod.price_ht'=>"LineUnitPrice",'cod.tva_tx'=>"LineVATRate",
		'cod.qty'=>"LineQty",'cod.total_ht'=>"LineTotalHT",'cod.total_tva'=>"LineTotalVAT",'cod.total_ttc'=>"LineTotalTTC",
		'cod.date_ouverture'=>"DateStart",'cod.date_ouverture_prevue'=>"DateStartPrevis",'cod.date_fin_validite'=>"EndValidity",'cod.date_cloture'=>"DateEnd",
		'p.rowid'=>'ProductId','p.ref'=>'ProductRef','p.label'=>'ProductLabel');

		$this->export_entities_array[$r]=array('s.rowid'=>"company",'s.nom'=>'company','s.address'=>'company','s.zip'=>'company',
		's.town'=>'company','c.code'=>'company','s.phone'=>'company','s.siren'=>'company','s.siret'=>'company','s.ape'=>'company',
		's.idprof4'=>'company','s.code_compta'=>'company','s.code_compta_fournisseur'=>'company','s.tva_intra'=>'company',
		'co.rowid'=>"Contract",'co.ref'=>"Contract",'co.datec'=>"Contract",'co.date_contrat'=>"Contract",'co.mise_en_service'=>"Contract",
		'co.fin_validite'=>"Contract",'co.date_cloture'=>"Contract",'co.note_private'=>"Contract",'co.note_public'=>"Contract",
		'cod.rowid'=>'contract_line','cod.label'=>"contract_line",'cod.description'=>"contract_line",'cod.price_ht'=>"contract_line",'cod.tva_tx'=>"contract_line",
		'cod.qty'=>"contract_line",'cod.total_ht'=>"contract_line",'cod.total_tva'=>"contract_line",'cod.total_ttc'=>"contract_line",
		'cod.date_ouverture'=>"contract_line",'cod.date_ouverture_prevue'=>"contract_line",'cod.date_fin_validite'=>"contract_line",'cod.date_cloture'=>"contract_line",
		'p.rowid'=>'product','p.ref'=>'product','p.label'=>'product');

		$this->export_TypeFields_array[$r]=array('s.rowid'=>"List:societe:nom",'s.nom'=>'Text','s.address'=>'Text','s.zip'=>'Text','s.town'=>'Text','c.code'=>'Text',
		's.phone'=>'Text','s.siren'=>'Text','s.siret'=>'Text','s.ape'=>'Text','s.idprof4'=>'Text','s.code_compta'=>'Text',
		's.code_compta_fournisseur'=>'Text','s.tva_intra'=>'Text',
		'co.ref'=>"Text",'co.datec'=>"Date",'co.date_contrat'=>"Date",'co.mise_en_service'=>"Date",
		'co.fin_validite'=>"Date",'co.date_cloture'=>"Date",'co.note_private'=>"Text",'co.note_public'=>"Text",
		'cod.label'=>"Text",'cod.description'=>"Text",'cod.price_ht'=>"Numeric",'cod.tva_tx'=>"Numeric",
		'cod.qty'=>"Numeric",'cod.total_ht'=>"Numeric",'cod.total_tva'=>"Numeric",'cod.total_ttc'=>"Numeric",
		'cod.date_ouverture'=>"Date",'cod.date_ouverture_prevue'=>"Date",'cod.date_fin_validite'=>"Date",'cod.date_cloture'=>"Date",
		'p.rowid'=>'List:Product:label','p.ref'=>'Text','p.label'=>'Text');


		$this->export_sql_start[$r]='SELECT DISTINCT ';
		$this->export_sql_end[$r]  =' FROM '.MAIN_DB_PREFIX.'societe as s';
		$this->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX.'c_country as c on s.fk_pays = c.rowid,';
		$this->export_sql_end[$r] .=' '.MAIN_DB_PREFIX.'contrat as co,';
		$this->export_sql_end[$r] .=' '.MAIN_DB_PREFIX.'contratdet as cod';
		$this->export_sql_end[$r] .=' LEFT JOIN '.MAIN_DB_PREFIX.'product as p on (cod.fk_product = p.rowid)';	
		$this->export_sql_end[$r] .=' WHERE co.fk_soc = s.rowid and co.rowid = cod.fk_contrat';
		$this->export_sql_end[$r] .=' AND co.entity = '.$conf->entity;
	}


	/**
	 *		Function called when module is enabled.
	 *		The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
	 *		It also creates data directories
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
	 */
	function init($options='')
	{
		global $conf;

		// Nettoyage avant activation
		$this->remove($options);

		$sql = array();

		return $this->_init($sql,$options);
	}

    /**
	 *		Function called when module is disabled.
	 *      Remove from database constants, boxes and permissions from Dolibarr database.
	 *		Data directories are not deleted
	 *
     *      @param      string	$options    Options when enabling module ('', 'noboxes')
	 *      @return     int             	1 if OK, 0 if KO
     */
    function remove($options='')
    {
		$sql = array();

		return $this->_remove($sql,$options);
    }

}
