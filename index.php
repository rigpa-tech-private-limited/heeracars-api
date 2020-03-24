<?php
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
// header('content-type: application/json');
include('lib/rest.model.php');
include('lib/textlocal.class.php');
if($_SERVER['REQUEST_METHOD']=="POST")
{
  $data = json_decode( file_get_contents( 'php://input' ), true );
  $res = json_encode(['success' => ["status"=>200, 'message'=>$data['mobile']]]);
  if(isset($data['service_name'])){
    if($data['service_name']=='getOTP'){
      if(isset($data['cc']) && isset($data['mobile'])){
        $cc = $data['cc'];
        $mobile = $data['mobile'];
        $cc_mobile = $cc.''.$mobile;
        $mobile_no = preg_replace('/[^0-9]/','',$cc_mobile);
        $restModel = new RESTAPIModel();
        $count = $restModel->getUserByMobile($mobile);
          if($count>0) {
          // generate OTP
          $otp = rand(100000,999999);
          // $textlocal = new Textlocal(TEXTLOCAL_USERNAME, TEXTLOCAL_PASSWORD);

          // $numbers = array($mobile_no);
          // $sender = 'TXTLCL';
          // $message = 'This is a your OTP '.$otp;

          // try {
          //     $result = $textlocal->sendSms($numbers, $message, $sender);
          // } catch (Exception $e) {
          //     echo json_encode(["status"=>"error", 'message'=>$e->getMessage()]);
          // }
          $updateCount = $restModel->updateOTP($otp,$mobile);
          if($updateCount>0){
            echo json_encode(["status"=>'success', 'message'=>'OTP : '.$otp]);
          } else {
            echo json_encode(["status"=>"error", 'message'=>"Try again later"]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Mobile Number"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='verifyOTP'){
      if(isset($data['otp'])){
        $restModel = new RESTAPIModel();
        $user = $restModel->verifyOTP();
        $hashed_password = password_hash($data['otp'], PASSWORD_BCRYPT, array('cost'=>5));
        if(count($count)>0) {
          $updateCount = $restModel->updateToken($data['otp'],$hashed_password);
          if($updateCount>0){
            echo json_encode(["status"=>'success', 'user'=>$user]);
          } else {
            echo json_encode(["status"=>"error", 'message'=>"Try again later"]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid OTP!"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }
  } else {
    echo json_encode(["status"=>"error", 'message'=>"Web service not available"]);
  }
}

if($_SERVER['REQUEST_METHOD']=="GET")
{
  if(isset($_GET['id']))
  {
    $id =  $_GET['id'];
    $restModel = new RESTAPIModel();
    $json = $restModel->get_single_user_info($id);
    if(empty($json))
    header("HTTP/1.1 404 Not Found");
    echo json_encode($json);
  }
  else{
    $restModel = new RESTAPIModel();
    $json = $restModel->get_all_user_list();
    if(empty($json))
    header("HTTP/1.1 404 Not Found");
    echo json_encode($json);
  }
}

?>