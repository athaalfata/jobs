<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Sliders Module
 *
 * @author Hendri Lubis, Widianto Gilang Ramadhan
 */
class Module_projects extends Module {

	public $version = '1.0';

	/**
	 * fungsi ini wajib dibuat 
	 * dengan konten array minimal seperti di bawah
	 */
	public function info()
	{
		return array(
			'name' => array( // nama modul, minimal default dalam bahasa inggris
				'en' => 'Outsource'
				),
			'description' => array( //deskripsi modul
				'en' => 'enable user for posting projects',
				'id' => 'Memungkinkan pengguna untuk memposting project.'
				),
			'frontend' => true, // karena memerlukan tampilan depan, maka set true
			'backend'  => true, // karena memerlukan tampilan belakang, maka set true
			'menu'	  => 'ipro', // simpan di bawah menu ipro
			'roles' => array( // set hak akses apa saja yang akan dibagi
						'post_projects', // digunakan untuk mengecek apakah user boleh post projects
						'publish_projects', // digunakan untuk mengecek apakah user boleh publish projects
						'bidding_projects' // digunakan untuk mengecek apakah user boleh menawar projects
						),
			'sections' => array( // ini daftar menu di dalam modul
				'jobs' => array( // nama identitas section
					'name' => 'Outsource', // nama section yang akan ditampilkan
					'uri' => 'admin/outsource', // alamat halaman untuk menu tersebut
					'shortcuts' => array( // tombol-tombol shortcut yang diperlukan untuk section tersebut
                        'create' => array( // ini contoh tombol create job
                            'name'  => 'Post Projects',
                            'uri'   => 'admin/outsource/create',
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
		if(! $this->db->table_exists('ipro_project')){	
			// definisikan tabel
			$tables['ipro_project'] = array(
				'project_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'category_id' => array('type' => 'INT', 'constraint' => 11),
				'title' => array('type' => 'VARCHAR', 'constraint' => 250, 'default' => 'Unnamed'),
				'description' => array('type' => 'TEXT'),
				'budget' => array('type' => 'VARCHAR', 'constraint' => 50, 'default' => 'not_sure'),
				'premium' => array('type' => 'VARCHAR', 'constraint' => 15, 'default' => 'none'),
				'status' => array('type' => 'ENUM', 'constraint' => array('pending','publish'), 'default' => 'pending'),
				'type' => array('type' => 'ENUM', 'constraint' => array('regular','trial','kontes_lomba'), 'default' => 'regular'),
				'date_expired' => array('type' => 'DATETIME'),
				'date_created' => array('type' => 'TIMESTAMP'),
				'date_updated' => array('type' => 'DATETIME')
			);
		};

		if(! $this->db->table_exists('ipro_project_skill')){	
			$tables['ipro_project_skill'] = array(
				'project_skill_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'project_id' => array('type' => 'INT', 'constraint' => 11),
				'skill_id' => array('type' => 'INT', 'constraint' => 11)
			);
		};

		if(! $this->db->table_exists('ipro_project_bidder')){	
			$tables['ipro_project_bidder'] = array(
				'project_bidder_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'project_id' => array('type' => 'INT', 'constraint' => 11),
				'bidder_id' => array('type' => 'INT', 'constraint' => 11),
				'date_bid' => array('type' => 'TIMESTAMP'),
				'deadline_bid' => array('type' => 'DATETIME'),
				'price_offer_bid' => array('type' => 'INT', 'constraint' => 11),
				'status' => array('type' => 'INT', 'constraint' => 11, 'default' => 0)
			);
		};

		if(! $this->db->table_exists('ipro_project_feature')){	
			$tables['ipro_project_feature'] = array(
				'project_feature_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'project_id' => array('type' => 'INT', 'constraint' => 11),
				'feature_id' => array('type' => 'INT', 'constraint' => 11)
			);
		};

		if(! $this->db->table_exists('ipro_project_attachment')){	
			$tables['ipro_project_attachment'] = array(
				'project_attachment_id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true),
				'project_id' => array('type' => 'INT', 'constraint' => 11),
				'attachment_id' => array('type' => 'INT', 'constraint' => 11)
			);
		};

		// Install semua tabel
		if ( ! $this->install_tables($tables)){
			return false;
		}

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
		$this->dbforge->drop_table('ipro_project');
		$this->dbforge->drop_table('ipro_project_skill');
		$this->dbforge->drop_table('ipro_project_bidder');
		$this->dbforge->drop_table('ipro_project_feature');
		$this->dbforge->drop_table('ipro_project_attachment');

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