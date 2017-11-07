<?php

class Migration_Add_fields_to_items extends CI_Migration {
	public function up () {		
		$fields = array(
					'invoice_start' => array(
							'type' => 'DATE' ,
							'after' => 'item_product_id'),

					'invoice_end' => array(
							'type' => 'DATE' ,
							'after' => 'invoice_start')
				);

		$this->dbforge->add_column('ip_invoice_items', $fields);
	}

	public function down () {
		$this->dbforge->drop_column('ip_invoice_items', 'invoice_start');
		$this->dbforge->drop_column('ip_invoice_items', 'invoice_end');			
	}
}