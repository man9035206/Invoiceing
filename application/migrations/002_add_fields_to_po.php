<?php

class Migration_Add_fields_to_po extends CI_Migration {
	public function up () {		
		$fields = array(
					'po_quantity' => array(
							'type' => 'INT' ,
                			'unsigned' => TRUE,
							'after' => 'product_price'),

					'po_billing_address' => array(
							'type' => 'INT' ,
                			'unsigned' => TRUE,
							'after' => 'po_quantity'),

					'po_shipping_address' => array(
							'type' => 'INT' ,
                			'unsigned' => TRUE,
							'after' => 'po_billing_address')
				);

		$this->dbforge->add_column('ip_products', $fields);
	}

	public function down () {
		$this->dbforge->drop_column('ip_products', 'po_quantity');
		$this->dbforge->drop_column('ip_products', 'po_billing_address');	
		$this->dbforge->drop_column('ip_products', 'po_shipping_address');			
	}
}