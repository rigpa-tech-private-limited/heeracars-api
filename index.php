<?php
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
// header('content-type: application/json');
include('lib/rest.model.php');
include('lib/textlocal.class.php');
if($_SERVER['REQUEST_METHOD']=="POST")
{
  $data = json_decode( file_get_contents( 'php://input' ), true );
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
      if(isset($data['otp']) && isset($data['mobile'])){
        $restModel = new RESTAPIModel();
        $user = $restModel->verifyOTP($data['otp'],$data['mobile']);
        $hashed_password = password_hash($data['otp'], PASSWORD_BCRYPT, array('cost'=>5));
        if($user!=null && count($user)>0) {
          $updateCount = $restModel->updateToken($data['otp'],$hashed_password);
          if($updateCount>0){
            $user['token'] = $hashed_password;
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

    if($data['service_name']=='listAgents'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $agents = $restModel->getAllAgentsList();
          if(count($agents)>0){
            echo json_encode(["status"=>'success', 'user'=>$agents]);
          } else {
            echo json_encode(["status"=>"error", 'message'=>"Try again later"]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='validateToken'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          echo json_encode(["status"=>"success", 'message'=>"valid token."]);
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='addAgent'){
      if(isset($data['name']) && isset($data['email']) && isset($data['mobile']) && isset($data['company']) && isset($data['location']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $insertFlag = $restModel->addAgent($data['name'], $data['mobile'], $data['email'], $data['company'], $data['location']);
          if($insertFlag){
            echo json_encode(["status"=>"success", 'message'=>"Agent added successfully."]);
          } else {
            echo json_encode(["status"=>"error", 'message'=>"Error occured. Try again later."]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
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