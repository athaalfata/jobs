<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Admin
 *
 * ini wajib ada, adalah class yang pertama kali diakses di halaman admin
 * ketika menu modul dipilih
 * tapi kalo ga akan ada tampilan halaman admin untuk modul ini mah ga usah bikin
 * 
 * Supaya tampilannya di admin, maka class harus extends Admin_Controller
 */
class Admin extends Admin_Controller {

	// section yang sedang aktif
	protected $section = 'jobs';

	// wajib ada, function ini pertama kali dijalankan ketika class dipanggil
	public function __construct()
	{
		parent::__construct();

		// load class, library dan language yang diperlukan disini
		$this->load->library('form_validation');
		$this->load->model('project/project_m');

		// set aturan validasi untuk setiap field di form
		// diambil dari property _validation_rules di model
		// tulis saja seperti ini
		$this->form_validation->set_rules($this->jobs_m->_validation_rules);

	}


	public function index()
	{
		//set the base/default where clause
		$base_where = array('status' => 'pending');

		// tambahkan nilai post ke base_where sebagai filter data
		if ($this->input->post('category'))	$base_where['category_id'] = $this->input->post('category');
		if ($this->input->post('status')) $base_where['status'] = $this->input->post('status');
		if ($this->input->post('premium')) $base_where['premium'] = $this->input->post('premium');
		if ($this->input->post('title')) $base_where['title'] = $this->input->post('title');

		// Create pagination links
		$total_rows = $this->jobs_m->count_by($base_where);
		$pagination = create_pagination('admin/project/index', $total_rows);

		// Ambil semua data jobs sesuai filter di atas
		$jobs = $this->jobs_m->limit($pagination['limit'])->get_many_by($base_where);

		// PENGATURAN DASAR TEMPLATE
		// lengkapnya lihat: http://docs.pyrocms.com/2.1/manual/developers/tools/template-library
		$this->template
			->title($this->module_details['name'])
			->set_partial('filters', 'admin/partials/filters')
			->set('pagination', $pagination)
			->set('jobs', $jobs);

		// tampilkan view
		// sebenernya mirip dengan $this->load->view('nama_view'), jadi ga usah bingung :)
		$this->template->build('admin/index');

	}
}