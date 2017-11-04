<?php

class Migration_Add_empid_to_po extends CI_Migration {
	public function up () {		
		$fields = array(
					'empid' => array(
							'type' => 'VARCHAR' ,
							'constraint' => 250,
							'after' => 'family_id')
				);

		$this->dbforge->add_column('ip_products', $fields);
	}

	public function down () {
		$this->dbforge->drop_column('ip_products', 'empid');		
	}
}