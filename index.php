<?php
error_reporting(E_ALL);
header("Access-Control-Allow-Origin: *");
// header('content-type: application/json');
include('lib/rest.model.php');
include('lib/textlocal.class.php');
if($_SERVER['REQUEST_METHOD']=="POST")
{
  $allowedAPIs = array("getOTP", "verifyOTP", "listAgents", "validateToken","addAgent", "editAgent", "deleteAgent", "changeStatusOfAgent","resetAgentLogin","getModelYearVariants","addQuotations","getModelYears","getModels","getBrands","approveQuotation","rejectQuotation","getQuotationDetail","getAllQuotations","updateProfile","testAPI");

  $data = json_decode( file_get_contents( 'php://input' ), true );
  if(isset($data['service_name']) && $data['service_name']!='' && in_array($data['service_name'], $allowedAPIs)){
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
          echo json_encode(["status"=>'success', 'user'=>$agents]);
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
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            echo json_encode(["status"=>"success", "user_id"=>$user['id'],  'message'=>"valid token."]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='addAgent'){
      if(isset($data['name']) && isset($data['email']) && isset($data['mobile']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $emailValidation = $restModel->validateEmail($data['email']);
          if($emailValidation || ($emailValidation==1)){
            echo json_encode(["status"=>"error", 'message'=>"Email ID already exists."]);
          } else {
            $mobileValidation = $restModel->validateMobile($data['mobile']);
            if($mobileValidation || ($mobileValidation==1)){
              echo json_encode(["status"=>"error", 'message'=>"Mobile number already exists."]);
            } else { 
              $insertFlag = $restModel->addAgent($data['name'], $data['mobile'], $data['email'], $data['company'], $data['location']);
              if($insertFlag){
                echo json_encode(["status"=>"success", 'message'=>"Agent details added successfully."]);
              } else {
                echo json_encode(["status"=>"error", 'message'=>"Agent details not added."]);
              }
            }
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='editAgent'){
      if(isset($data['id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $updateCount = $restModel->updateAgent($data['name'], $data['company'], $data['location'], $data['id']);
          if($updateCount > 0){
            echo json_encode(["status"=>"success", 'message'=>"Agent details updated successfully."]);
          } else {
            echo json_encode(["status"=>"error", 'message'=>"Agent details not updated"]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='changeStatusOfAgent'){
      if(isset($data['id']) && isset($data['status']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $updateCount = $restModel->updateStatusOfAgent($data['status'], $data['id']);
          if($updateCount > 0){
            echo json_encode(["status"=>"success", 'message'=>"status changed."]);
          } else {
            echo json_encode(["status"=>"error", 'message'=>"status not updated"]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='deleteAgent'){
      if(isset($data['id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $updateCount = $restModel->deleteAgent($data['id']);
          if($updateCount > 0){
            echo json_encode(["status"=>"success", 'message'=>"agent removed."]);
          } else {
            echo json_encode(["status"=>"error", 'message'=>"agent not removed"]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='resetAgentLogin'){
      if(isset($data['id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $agents = $restModel->resetAgentLogin($data['id']);
          echo json_encode(["status"=>'success', 'user'=>$agents]);
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }
    
    if($data['service_name']=='getBrands'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $brands = $restModel->getBrands();
          echo json_encode(["status"=>'success', 'brands'=>$brands]);
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getModels'){
      if(isset($data['make_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $models = $restModel->getModels($data['make_id']);
          echo json_encode(["status"=>'success', 'models'=>$models]);
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getModelYears'){
      if(isset($data['model_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $modelYears = $restModel->getModelYears($data['model_id']);
          echo json_encode(["status"=>'success', 'modelYears'=>$modelYears]);
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getModelYearVariants'){
      if(isset($data['model_id']) && isset($data['year_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $modelYearVariants = $restModel->getModelYearVariants($data['model_id'],$data['year_id']);
          echo json_encode(["status"=>'success', 'modelYearVariants'=>$modelYearVariants]);
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='addQuotations'){
      if(isset($data['user_id']) &&  isset($data['make_id']) &&  isset($data['make_display']) && isset($data['model_id']) && isset($data['model_display']) &&  isset($data['year_id']) &&  isset($data['year']) &&  isset($data['variant_id']) &&  isset($data['variant_display']) && isset($data['car_color']) && isset($data['fuel_type']) && isset($data['car_kms']) && isset($data['car_owner']) && isset($data['is_replacement']) && isset($data['structural_damage']) && isset($data['structural_damage_desc']) && isset($data['insurance_date']) && isset($data['refurbishment_cost']) && isset($data['requested_price']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $insertFlag = $restModel->addQuotations($data['user_id'], $data['make_id'],$data['make_display'], $data['model_id'], $data['model_display'], $data['year_id'], $data['year'], $data['variant_id'], $data['variant_display'],$data['car_color'],$data['fuel_type'],$data['car_kms'],$data['car_owner'],$data['is_replacement'],$data['structural_damage'],$data['structural_damage_desc'],$data['insurance_date'],$data['refurbishment_cost'],$data['requested_price']);
          if($insertFlag){
            echo json_encode(["status"=>"success", 'message'=>"Quotation request sent successfully."]);
          } else {
            echo json_encode(["status"=>"error", 'message'=>"Quotation request not sent."]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='approveQuotation'){
      if(isset($data['quotation_id']) && isset($data['approved_price']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->approveQuotation($data['quotation_id'],$data['approved_price'],$user['id']);
            if($updateCount > 0){
              echo json_encode(["status"=>"success", 'message'=>"Quotation approved."]);
            } else {
              echo json_encode(["status"=>"error", 'message'=>"Approval faild"]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='rejectQuotation'){
      if(isset($data['quotation_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->rejectQuotation($data['quotation_id'],$user['id']);
            if($updateCount > 0){
              echo json_encode(["status"=>"success", 'message'=>"Quotation rejected."]);
            } else {
              echo json_encode(["status"=>"error", 'message'=>"rejection failed"]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getAllQuotations'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            if($user['role']=='admin'){
              $allQuotations = $restModel->getAllQuotations();
            } else {
              $allQuotations = $restModel->getAllQuotations($user['id']);
            }
            echo json_encode(["status"=>'success', 'quotations'=>$allQuotations]);
          }
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getQuotationDetail'){
      if(isset($data['token']) && isset($data['quotation_id'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $quotation = $restModel->getQuotationDetail($data['quotation_id']);
          echo json_encode(["status"=>'success', 'quotation'=>$quotation]);
        } else {
          echo json_encode(["status"=>"error", 'message'=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error", 'message'=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='updateProfile'){
      if(isset($data['id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->updateProfile($data['name'], $data['company'], $data['location'], $user['id']);
            if($updateCount > 0){
              echo json_encode(["status"=>"success", 'message'=>"Profile updated successfully."]);
            } else {
              echo json_encode(["status"=>"error", 'message'=>"Profile not updated"]);
            }
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