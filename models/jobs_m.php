<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Jobs Model
 * 
 * @author Toni Haryanto
 *
 * nama classnya bebas, tapi biar seragam,-
 * nama model = nama controller ditambah akhiran _m
 */
class Jobs_m extends MY_Model
{
	/**
	 * Array berisi aturan validasi field pada form
	 * lengkapnya lihat http://ellislab.com/codeigniter/user-guide/libraries/form_validation.html
	 * pokoknya CI banget! :P
	 *
	 * @access	public
	 * @var		array
	 */
	public $_validation_rules = array(
		array(
			'field' => 'title', // nama fieldnya, namanya sama dengan nama kolom di tabel
			'label' => 'Judul', // Label pada form untuk field ini
			'rules' => 'trim|required' // aturan validasi untuk field ini
		),
		array(
			'field' => 'description',
			'label' => 'Deskripsi',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'category_id',
			'label' => 'Kategori',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'jobs_skill',
			'label' => 'Keahlian',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'language',
			'label' => 'Bahasa Komunikasi',
			'rules' => 'trim'
		),
		array(
			'field' => 'budget',
			'label' => 'Budget',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'status',
			'label' => 'Status',
			'rules' => 'trim'
		)

	);

	
}