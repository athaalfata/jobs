<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*ini adalah kelas yang digunakan untuk melakukan aktivitas yang berhubungan dengan project,
seperti posting project, melihat project, mengubah project, mencari project*/

class Project extends Public_Controller{

	function __construct() {
        parent::__construct();

	}

	/* fungsi index untuk menampilkan project secara default */

	function index(){

	}

	public function jendela_tambah_project(){
		$this->load->view('add_project_v');
	}

	public function tambah_project(){
			//Menerima Masukkan dari view
			$tanggal = $this->input->post('tanggal',true);
			$bulan = $this->input->post('bulan',true);
			$tahun = $this->input->post('tahun',true);
			$date_expired = $tahun."-".$bulan."-".$tanggal;
			$data['title'] = $this->input->post('title',true);
			$data['category_id'] = $this->input->post('category_id',true);
			$data['description'] = $this->input->post('description',true);
			$data['budget'] = $this->input->post('budget',true);
			$data['type'] = $this->input->post('type',true);
			$data['date_expired'] = $date_expired;
			redirect(project/index);
    }

    public function cari_project()
}