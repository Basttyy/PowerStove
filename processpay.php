<?php
    $page_tittle = "User Authentication - Hompage";
    include_once "partials/headers.php";

?>

<?php include_once "partials/footers.php";?>
</body>
</html>

<script type="text/javascript">
    var orderObj = {
        useremail: "<?php echo $_GET['email'];?>",
        userid: "<?php echo $_GET['usrid'];?>",
        username: "<?php echo $_GET['usr'];?>",
        paycategory: "<?php echo $_GET['payref'];?>",
        amount: "1000000",
        orderid: "ordweb000001",
        cartid: "kd12345"
        //other params you want to save
    };
    function saveOrderThenPayWithPaystack(){
        //window.alert('trying to pay');
        //send the data to save to database using post
        window.alert('making payment');
        var posting = $.post('/save_order.php', orderObj);

        posting.done(function(data){
            //check result from the attempt
            payWithPayStack(data);
        });
        posting.fail(function(data){
            //payWithPayStack(data);
            //and if it failed to save do this
            window.alert('Failed to save data');
        });
    };
    function payWithPayStack(data){
        window.alert('making payment');
        var handler = PaystackPop.setup({
            //This assumes you already created a constant named
            //PAYSTACK_PUBLIC_KEY with your public key from the
            //Paystack dashboard. You can as well just paste it
            //instead of creating the constant
            key: 'pk_test_6a23d42a2ac9cd58a44a1d32c3e9a255d71b6418',
            email: orderObj.email,
            amount: orderObj.amount,
            metadata: {
                cartid: orderObj.cartid,
                orderid: '',
                custom_fields: [
                    {
                        display_name: "Paid on",
                        Variable_name: "paid_on",
                    },
                    {
                        display_name: "Paid via",
                        variable_name: "paid_via",
                        value: "Online Payment"
                    }
                ]
            },
            callback: function(response){
                //post to server to verify transaction before giving value
                var verifying = $.get('/verify.php?reference=' + response.reference);
                verifying.done(function(data){
                    //give value saved in data
                    verifyObj = json_decode(data);
                    if(verifyObj.verified == true){
                        echo 'alert("your payment was successful");';
                    }
                });
            },
            onClose: function(){
                alert('Click "Pay Now" to retry payment.');
            }
        });
        handler.openIframe();
    }
</script>
