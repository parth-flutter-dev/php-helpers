<?php

require_once 'firebasepushmanager.php';

class CLS_SendPushNotification
{

    private $db, $objGeneral, $con, $objDbCon, $now, $pushManager;
    public function __construct($con, $objGeneral, $objDbCon)
    {
        $this->objGeneral = $objGeneral;
        $this->con = $con;
        $this->objDbCon = $objDbCon;
        $this->now = $this->objGeneral->getCurrentUTCTime($this->con);
        $this->pushManager = new FirebasePushManager();
    }

    public function __destruct()
    {

    }

    public function sendPushForRemainder($receiver_token, $tittle, $txtMsg = "")
    {
        $user = $this->getUserFromID($receiver_token);
        if ($user != false) {
            if (!empty($user["device_push_token"])) {
                $objPushMessage = new stdClass();
                $objPushMessage->device_token = $user["device_push_token"];
                $objPushMessage->device_type = $user["device_type"];
                $objPushMessage->message = $txtMsg;
                $objPushMessage->title = $tittle;
                $objPushMessage->badge = 0;

                $payload = array(
                    "type" => "notification"
                );

                $this->pushManager->sendSinglePushNotification($objPushMessage, $payload);
            }
        }
    }
    public function sendTestPush($userdata)
    {

        // $this->sendPushToMultipleUser(["e-qwRIANTX2ejrWwquCeOr:APA91bHQLqUjBaDk3SEWeuu4VAmaTK8O9bq-biOqi0x_5M6GtYxHexar3fNoemGCE_FfNwYN5_eSurbvq8ggyYfKRz2Obr0T9IkkRcnrUSUKyccLl55PTco","c5kXsJ2N_kZHkC9LLXcLU1:APA91bFDR15gFuNp_dJs0QKzNNfHio-J4YIJBkgWXDFK0Nw4HEWJRcOGr0ly6B1o9ACLNnS8-1nJkLbcR7pBlz3CFNfrsciq05xXaKWcfYuLbCdfoxnYpU4"],"hello dear","test dear");
        // die;
        if (!empty($userdata->device_push_token)) {

            $objPushMessage = new stdClass();
            $objPushMessage->device_token = $userdata->device_push_token;
            $objPushMessage->device_type = $userdata->device_type;
            $objPushMessage->message = "test";
            $objPushMessage->title = "cccccc";
            $objPushMessage->badge = 0;

            $payload = array(
                "type" => "notification"
            );

            $test=$this->pushManager->sendSinglePushNotification($objPushMessage, $payload);
            print_r($test);
            return;
        }

    }

    public function sendPushOnPushToken($receiver_push_token, $device_type, $tittle = "Mama Mode", $txtMsg = "")
    {
        $objPushMessage = new stdClass();
        $objPushMessage->device_token = $receiver_push_token;
        $objPushMessage->device_type = $device_type;
        $objPushMessage->message = $txtMsg;
        $objPushMessage->title = $tittle;
        $objPushMessage->badge = 0;
        $payload = array(
            "type" => "notification"
        );

        $this->pushManager->sendSinglePushNotification($objPushMessage, $payload);

    }

    public function sendPushToMultipleUser($tokenArray, $tittle = "Mama Mode", $txtMsg = "")
    {
        $payload = [ 
            "message" => $txtMsg,
            "title" => $tittle
        ];
        $this->pushManager->sendMultiplePushNotification($tokenArray,$payload);

    }
     

    public function getUserFromID($userID)
    {
        $query = "SELECT * FROM `user` WHERE `user_token` = '$userID' and `is_active` = 1 and `is_deleted` = 0 limit 1";
        $r = $this->con->rawQuery($query);
        if ($this->con->count > 0) {
            return $r[0];
        } else {
            return false;
        }
    }

}
