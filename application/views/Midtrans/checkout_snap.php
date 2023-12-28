<html>
<title>Checkout</title>
  <head>
    <script type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="SB-Mid-client-fjJ6_8NurAYUADr9"></script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  </head>
  <body>

    
    <form id="payment-form" method="post" action="<?php echo base_url();?>midtrans/snap/finish">
      <input type="hidden" name="result_type" id="result-type" value=""></div>
      <input type="hidden" name="result_data" id="result-data" value=""></div>
    </form>
    
    <button id="pay-button">Pay!</button>
    <script type="text/javascript">
  
    $('#pay-button').click(function (event) {
      event.preventDefault();
      $(this).attr("disabled", "disabled");
    
    $.ajax({
      url: '<?php echo base_url();?>midtrans/snap/token',
      cache: false,

      success: function(data) {
        //location = data;        
        var resultType = document.getElementById('result-type');
        var resultData = document.getElementById('result-data');

        function changeResult(type,data){
          $("#result-type").val(type);
          $("#result-data").val(JSON.stringify(data));
          //resultType.innerHTML = type;
          //resultData.innerHTML = JSON.stringify(data);
        }

        snap.pay(data, {
          
          onSuccess: function(result){
            changeResult('success', result);
            $("#payment-form").submit();
          },
          onPending: function(result){
            changeResult('pending', result);
            $("#payment-form").submit();
          },
          onError: function(result){
            changeResult('error', result);
            $("#payment-form").submit();
          }
        });
      }
    });
  });

  </script>


</body>
</html>
