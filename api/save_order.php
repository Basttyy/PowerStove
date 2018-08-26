<?php
     require __DIR__ . '/../resource/session.php';
     require __DIR__ . '/../resource/database.php';
     require __DIR__ . '/../resource/utilities.php';
    //prepare variables for database connection
    if(isset($_POST['userid'])){
        $userid = $_POST['userid'];
        $email = $_POST['email'];
        $username = $_POST['username'];
        $paycategory = $_POST['paycategory'];

        $sql = "SELECT * FROM users WHERE id = :id";
        //use pdo to sanitize data
        $statement = $db->prepare($sql);
        //add data into database
        $statement->execute(array(':id' => $userid));
        if($row = $statement->fetch()){
            $sql = $db->prepare("INSERT INTO payments(user_id, trans_id, order_type, pay_category, amount, order_date)
                                VALUES(:id, trans_id, order_type, pay_category, amount, now())");
            $sql->execute(array(':id'=>$userid, ':trans_id'=>$trasnid,  ':pay_category'=>$paycategory, ':amount'=>$amount));
            if($sql->rowcount()===1){
                if($paycategory == "purch_install"){
                    $orderObj->amount = 1000000;
                }else if($paycategory == "purch_full"){
                    $orderObj->amount = 2800000;
                }else if($paycategory == "purch_renew"){
                    $orderObj->amount = 400000;
                }
                $orderObj->orderid = $paycategory.(date(""));
                $orderObj->cartid = "cart".(date(""));
            }
        }
    }
    return json_encode($orderObj);
?> 