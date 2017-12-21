<?php

class Migration_Add_new_fields_to_po extends CI_Migration {
	public function up () {		
		$fields = array(
					'po_pos' => array(
							'type' => 'VARCHAR' ,                			
                            'constraint' => '100',
							'after' => 'po_shipping_address'),

					'po_state_code' => array(
							'type' => 'INT' ,                			
                            'constraint' => '10',
							'after' => 'po_pos'),

					'po_reverse_charge' => array(
							'type' => 'po_reverse_charge' ,                			
                            'constraint' => '3',
							'after' => 'po_state_code')
				);

		$this->dbforge->add_column('ip_products', $fields);
	}

	public function down () {
		$this->dbforge->drop_column('ip_products', 'po_pos');
		$this->dbforge->drop_column('ip_products', 'po_state_code');	
		$this->dbforge->drop_column('ip_products', 'po_reverse_charge');			
	}
}