<html lang="en">

<head>


  <title>Download Invoices</title>

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

  <div style="width:520px;margin:0px auto;margin-top:30px;height:500px;">

  <h2>Download Invoices</h2>
  <?php
  $id = 1;
  ?>
  <select class="itemName form-control" style="width:500px" name="itemName"></select>
  <a href="/ip/index.php/download_all_invoices/downloadInvoices/download".'/'.$id >Dowload All Invoices</a>

</div>


<script type="text/javascript">


      $('.itemName').select2({
			placeholder: '--- Select Item ---',
			ajax: {
				url: '/ip/index.php/download_all_invoices/downloadInvoices/index',
				dataType: 'json',	
				delay: 250,
				minimumInputLength: 2,
				processResults: function (data) {
				return {

              			results: data
              		};

          },

          cache: true

        }

      });


</script>




</head>

<body>