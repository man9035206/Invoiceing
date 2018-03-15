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
		if(!is_dir($estructure))
		{
			$oldmask = umask(0);
			mkdir($estructure, 0777);
	    	umask($oldmask);
   		} 
   		else 
   		{
   			 die("Folder is already exist");
   		}
   		if ($this->input->post('btn_submit')) 
		{
			$this->load->library('zip');
			$this->zip;
            $data =  $this->Mdi_download_invoices->download_pdf_files($this->input->post('client_id'));    
    	    $data['d'] = $data;
   			foreach ($data as $d)
			{
				 //$ids = $d->client_id;
		 	     $ids = $d->invoice_id;
		 	     $nId['invoice_id'] = $ids;
				 $mpdf = new \Mpdf\Mpdf();
 				 $html = $this->load->view('download_all_invoices/pdf',$nId,true);
 				 $mpdf->WriteHTML($html);
 				 $mpdf->Output($estructure.$d->invoice_id,'F');
			}
			$this->zip->read_dir($estructure);
			$this->zip->archive('/var/www/my_backup2.zip');
			$this->zip->download('my_backup.zip');
			}	
		 $this->layout->buffer('content', 'download_all_invoices/index')->render();
	}
		
	public function getdata()
	{
		$data =  $this->Mdi_download_invoices->download_pdf_files(12);		 
		foreach ($data as $d)	
			{
				 
 				 $this->load->view('download_all_invoices/pdf',$d);
 				
			}
 				
	}

	public function getdataw()
	{
		 $data =  $this->Mdi_download_invoices->download_pdf_files(12);    
    	 $data['d'] = $data;
    	
    	 foreach ($data as $d)
		 {
		 	$ids = $d->client_id;
		 	$ids = $d->invoice_i;
		 	$nId['invoice_id'] = $ids;
		 	$this->load->view('download_all_invoices/pdf',$nId);
		 	$this->load->view('download_all_invoices/pdf',$nId);
			
		 }
	}
		
}





