public function downloadFiles()
	{
		$filename = time()."_order.pdf";
 
		$html =$this->load->view('download_all_invoices/pdf', $data, true);
		$this->load->helper('mpdf');
		$this->mpdf->pdf->WriteHTML($html);
 
		$this->mpdf->pdf->Output("./uploads/".$filename, "F");
		// if ($this->input->post('btn_submit'))
		// {
		// 	$filename = "ok";
  //       $data = array(
  //             'results' => $this->Mdi_download_invoices->download_pdf_files($this->input->post('client_id')));
  //       for ($i = 0; $i<count($data); $i++) {
		// 	$html = $this->load->view('download_all_invoices/pdf', $data, true);
		// 	$this->load->helper('mpdf');
			
		// 	pdf_create($html, trans($d->invoice_id), true);

  //       }
        
		// }
		// $this->layout->buffer('content', 'download_all_invoices/index')->render();
    	  
    }