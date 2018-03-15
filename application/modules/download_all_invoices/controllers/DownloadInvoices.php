<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');


class DownloadInvoices extends Admin_Controller
{

	  public function __construct()
      {
        parent::__construct();
       
        $this->load->model('download_all_invoices/Mdi_download_invoices');
      }

	public function index()
	{
		$this->layout->set('invoices', $invoices);
        $this->layout->buffer('content', 'download_all_invoices/index')->render();
		$this->load->view('download_all_invoices/index');
	}

	public function downloadFiles()
	{
		$estructure = "/home/administrator/Downloads/pdfs_new12/";
		// if(!is_dir($estructure))
		// {
		// 	$oldmask = umask(0);
		// 	mkdir($estructure, 0777);
	 //    	umask($oldmask);
  //  		} 
  //  		else 
  //  		{
  //  			 die("Folder is already exist");
  //  		}
   		if ($this->input->post('btn_submit')) 
		{
			$this->load->library('zip');
			$this->zip;
             $data = array(
             	 'results' => $this->Mdi_download_invoices->download_pdf_files(9));
            //die( json_encode($data['results']));
   			foreach ($data['results'] as $d)
			{
				 $values['client_name'] = $d->client_name; 
				 $values['invoice_number'] = $d->invoice_number;
				 $values['invoice_date_created'] = $d->invoice_date_created;
				 $values['invoice_date_due'] = $d->invoice_date_due;
				 // $values['product_no'] = $d->product_no;
				 // $values['product_start'] = $d->product_start;
				 // $values['product_end'] =  $d->product_end;
				 $values['payment_method'] =  $d->payment_method;

				 $values['product_no'] =  $d->product_no;


				 $mpdf = new \Mpdf\Mpdf();
 				 $html = $this->load->view('download_all_invoices/pdf',$values,true);
 				 $mpdf->WriteHTML($html);
 				 $mpdf->Output();
			}
			$this->zip->read_dir($estructure);
			$this->zip->archive('/var/www/my_backup2.zip');
			$this->zip->download('my_backup.zip');
			}	
		 $this->layout->buffer('content', 'download_all_invoices/index')->render();
	}
		
	public function getdata()
	{
		$data =  $this->Mdi_download_invoices->download_pdf_files(9);		 
		foreach ($data as $d)	
			{
				 $values['client_name'] = $d->client_name; 
				 $values['invoice_number'] = $d->invoice_number;
				 $values['invoice_date_created'] = $d->invoice_date_created;
				 $values['invoice_date_due'] = $d->invoice_date_due;
				 $values['product_no'] = $d->product_no;
				 $values['product_start'] = $d->product_start;
				 $values['product_end'] =  $d->product_end;
 				 $this->load->view('download_all_invoices/pdf',$values);
 				
			}
 				
	}
		
}





