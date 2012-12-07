<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sliders Module
 *
 * @author Toni Haryanto
 */
class Module_Jobs extends Module {

	public $version = '1.0';

	/**
	 * fungsi ini wajib dibuat 
	 * dengan konten array minimal seperti di bawah
	 */
	public function info()
	{
		return array(
			'name' => array( // nama modul, minimal default dalam bahasa inggris
				'en' => 'Jobs',
				'id' => 'Lowongan Kerja'
				),
			'description' => array( //deskripsi modul
				'en' => 'Enable users to post jobs.',
				'id' => 'Memungkinkan pengguna untuk memposting lowongan kerja.'
				),
			'frontend' => true, // karena memerlukan tampilan depan, maka set true
			'backend'  => true, // karena memerlukan tampilan belakang, maka set true
			'menu'	  => 'ipro', // simpan di bawah menu ipro
			'roles' => array( // set hak akses apa saja yang akan dibagi
						'post_jobs', // digunakan untuk mengecek apakah user boleh post jobs
						'publish_jobs' // digunakan untuk mengecek apakah user boleh publish jobs
						),
			'sections' => array( // ini daftar menu di dalam modul
				'jobs' => array( // nama identitas section
					'name' => 'Jobs', // nama section yang akan ditampilkan
					'uri' => 'admin/jobs', // alamat halaman untuk menu tersebut
					'shortcuts' => array( // tombol-tombol shortcut yang diperlukan untuk section tersebut
                        'create' => array( // ini contoh tombol create job
                            'name'  => 'Post Job',
                            'uri'   => 'admin/jobs/create',
                            'class' => 'add'
                            )
                        )
					),
				),
			);
	}

	/**
	 * fungsi ini wajib ada supaya modul kita bisa diinstal di pyrocms
	 * di dalamnya dipersiapkan segala sesuatu yang diperlukan oleh modul
	 * seperti skema database, data awal, data pengaturan, dan sebagainya
	 */
	public function install()
	{
		$tables = array();

		// cek apakah tabel sudah ada di database
		// lakukan hal yang sama untuk tabel yang lain
		// untuk keseragaman, setiap nama tabel diawali oleh ipro_ ea :)
		if(! $this->db->table_exists('ipro_jobs')){	
			// definisikan tabel
			$tables['ipro_jobs'] = array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'employer_id' => array('type' => 'INT', 'constraint' => 11),
				'category_id' => array('type' => 'INT', 'constraint' => 11),
				'title' => array('type' => 'VARCHAR', 'constraint' => 250, 'default' => 'Unnamed'),
				'description' => array('type' => 'TEXT'),
				'budget' => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => 'not_sure'),
				'premium' => array('type' => 'VARCHAR', 'constraint' => 15, 'default' => 'none'),
				'status' => array('type' => 'ENUM', 'constraint' => array('pending','publish'), 'default' => 'pending'),
				'date_expired' => array('type' => 'DATETIME'),
				'date_created' => array('type' => 'TIMESTAMP'),
				'date_updated' => array('type' => 'DATETIME')
			);
		};

		// beberapa Tabel berikut akan berhubungan dengan modul yang lain, 
		// jadi tinggal dipake saja, ga perlu bikin lagi
		if(! $this->db->table_exists('ipro_job_category')){	
			$tables['ipro_job_category'] = array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'category' => array('type' => 'VARCHAR', 'constraint' => 50),
				'description' => array('type' => 'VARCHAR', 'constraint' => 250)
			);
		};

		if(! $this->db->table_exists('ipro_skill')){	
			$tables['ipro_skill'] = array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'skill' => array('type' => 'VARCHAR', 'constraint' => 50),
				'parent_id' => array('type' => 'INT', 'constraint' => 11)
			);
		};

		if(! $this->db->table_exists('ipro_budget')){	
			$tables['ipro_budget'] = array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'budget' => array('type' => 'VARCHAR', 'constraint' => 30)
			);
		};

		if(! $this->db->table_exists('ipro_language')){	
			$tables['ipro_language'] = array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'code' => array('type' => 'VARCHAR', 'constraint' => 3),
				'language' => array('type' => 'VARCHAR', 'constraint' => 30)
			);
		};

		if(! $this->db->table_exists('ipro_jobs_language')){	
			$tables['ipro_jobs_language'] = array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'jobs_id' => array('type' => 'INT', 'constraint' => 11),
				'language_id' => array('type' => 'INT', 'constraint' => 11)
			);
		};

		if(! $this->db->table_exists('ipro_jobs_skill')){	
			$tables['ipro_jobs_skill'] = array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'jobs_id' => array('type' => 'INT', 'constraint' => 11),
				'skill_id' => array('type' => 'INT', 'constraint' => 11)
			);
		};

		// Install semua tabel
		if ( ! $this->install_tables($tables)){
			return false;
		}

		// kalo ada data awal yang mesti dimasukkan ke tabel tertentu, tinggal masukin aja
		$this->db->insert('ipro_budget', array('budget'=>'not_sure'));

		// kalo tidak ada masalah, berarti instalasi sukses
		return true;
	}

	/**
	 * fungsi ini harus ada supaya modul kita harus diistal ulang
	 * 
	 */
	public function uninstall()
	{
		// ini untuk menghapus tabel beserta semua datanya
		// hati-hati, sebaiknya gunakan ini hanya dalam proses development
		// setelah semua modul dipasang online, komentari semua baris kode hapus tabel ini -
		// dan biarkan user menghapus tabel secara manual
		$this->dbforge->drop_table('ipro_jobs');
		$this->dbforge->drop_table('ipro_job_category');
		$this->dbforge->drop_table('ipro_skill');
		$this->dbforge->drop_table('ipro_budget');
		$this->dbforge->drop_table('ipro_language');
		$this->dbforge->drop_table('ipro_jobs_language');
		$this->dbforge->drop_table('ipro_jobs_skill');

		return true;
	}

	/**
	 * fungsi ini digunakan bila modul sudah online
	 * dan fitur modul diupgrade sehingga memerlukan modifikasi pada database
	 * 
	 */
	public function upgrade($old_version)
	{
		// Upgrade Logic
		return true;
	}
}