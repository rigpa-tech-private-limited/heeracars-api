<?php
error_reporting(E_ERROR | E_PARSE);
header("Access-Control-Allow-Origin: *");
// header('content-type: application/json');
include('lib/rest.model.php');
include('lib/textlocal.class.php');
if($_SERVER['REQUEST_METHOD']=="POST")
{
  $allowedAPIs = array("addStudent","editQuotation","getQuotationsCount","getNotifications","getNotificationsCount","validatePin","validateMobileNumber","addQuotationImage","updateQuotationImage", "getOTP", "verifyOTP", "listAgents", "validateToken","addAgent", "editAgent", "deleteAgent", "changeStatusOfAgent","resetPin","getModels","getBrands","addQuotations","getModelVariants","getVariantYears","resubmitQuotation","approveQuotation","rejectQuotation","soldQuotation","getQuotationDetail","getAllQuotations","updateProfile","getComments","deleteComments","editComments","addComments","testAPI");

  $data = json_decode( file_get_contents( 'php://input' ), true );
  // echo json_encode(["data"=>($_REQUEST)]);
  if($_REQUEST['service_name']=='addStudent'){
    if(isset($_REQUEST['name']) && isset($_REQUEST['registration_no']) && isset($_REQUEST['phone']) && isset($_REQUEST['email'])){
      $restModel = new RESTAPIModel();
      $insertFlag = $restModel->addStudent($_REQUEST['name'], $_REQUEST['registration_no'], $_REQUEST['phone'], $_REQUEST['email']);
      if($insertFlag){
        echo json_encode(["status"=>"success", "status_code"=>"200","message"=>"Student details added successfully."]);
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Student details not added."]);
      }
    } else {
      echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
    }
  }
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
          //     echo json_encode(["status"=>"error","status_code"=>"402", "message"=>$e->getMessage()]);
          // }
          $updateCount = $restModel->updateOTP($otp,$mobile);
          if($updateCount>0){
            echo json_encode(["status"=>'success', "status_code"=>"200", "message"=>'OTP : '.$otp]);
          }
        } else {
          echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid Mobile Number"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='verifyOTP'){
      if(isset($data['otp']) && isset($data['mobile'])){
        $restModel = new RESTAPIModel();
        $user = $restModel->verifyOTP($data['otp'],$data['mobile']);
        $hashed_password = password_hash($data['otp'], PASSWORD_BCRYPT, array('cost'=>5));
        if($user!=null && count($user)>0) {
          $push_token = '';
          if(isset($data['device_token'])){
            $push_token = $data['device_token'];
          }
          $updateCount = $restModel->updateToken($data['otp'],$hashed_password,$push_token);
          if($updateCount>0){
            $user['token'] = $hashed_password;
            echo json_encode(["status"=>'success', "status_code"=>"200", 'user'=>$user]);
          }
        } else {
          echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid OTP!"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='validateMobileNumber'){
      if(isset($data['mobile1'])){
        $restModel = new RESTAPIModel();
        $user = $restModel->validateMobileNumber($data['mobile1'],$data['mobile2']);
        if($user!=null && count($user)>0) {
          echo json_encode(["status"=>'success', "status_code"=>"200", 'mobile_number'=>$user['mobile']]);
        } else {
          echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid Mobile Number(s)!"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='validatePin'){
      if(isset($data['pin'])){
        $restModel = new RESTAPIModel();
        $user = $restModel->validatePin($data['pin']);
        $hashed_password = password_hash($data['pin'], PASSWORD_BCRYPT, array('cost'=>5));
        if($user!=null && count($user)>0) {
          $push_token = '';
          if(isset($data['device_token'])){
            $push_token = $data['device_token'];
          }
          $updateCount = $restModel->updateTokenByPin($data['pin'],$hashed_password,$push_token);
          if($updateCount>0){
            $user['token'] = $hashed_password;
            echo json_encode(["status"=>'success', "status_code"=>"200", 'user'=>$user]);
          }
        } else {
          echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid PIN!"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='listAgents'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $agents = $restModel->getAllAgentsList();
          echo json_encode(["status"=>'success', "status_code"=>"200", 'user'=>$agents]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='validateToken'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            echo json_encode(["status"=>"success", "status_code"=>"200", "user_id"=>$user['id'],  "message"=>"valid token."]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='addAgent'){
      if(isset($data['name']) && isset($data['mobile']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $mobileValidation = $restModel->validateMobile($data['mobile']);
          if($mobileValidation || ($mobileValidation==1)){
            echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Mobile number already exists."]);
          } else {            
            $pin = $restModel->generateUniquePIN();
            $user = $restModel->getUserByToken($data['token']);
            if(count($user) > 0){
              $insertFlag = $restModel->addAgent($data['name'], $data['mobile'], $data['email'], $data['company'], $data['location'], $pin, $user['id']);
              if($insertFlag){
                if($data['email']!=''){
                  $sendMail = $restModel->sendWelcomeMail($data['name'],$data['email'],$pin,0);
                }
                echo json_encode(["status"=>"success", "status_code"=>"200", "name"=>$data['name'], "pin"=>$pin, "message"=>"Agent details added successfully."]);
              } else {
                echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Agent details not added."]);
              }
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='editAgent'){
      if(isset($data['id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $updateCount = $restModel->updateAgent($data['name'],$data['mobile'],$data['email'], $data['company'], $data['location'], $data['id']);
          echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Agent details updated successfully."]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='changeStatusOfAgent'){
      if(isset($data['id']) && isset($data['status']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $updateCount = $restModel->updateStatusOfAgent($data['status'], $data['id']);
          if($updateCount > 0){
            echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"status changed."]);
          } else {
            echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"status not updated"]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='deleteAgent'){
      if(isset($data['id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $updateCount = $restModel->deleteAgent($data['id']);
          if($updateCount > 0){
            echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"agent removed."]);
          } else {
            echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"agent not removed"]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='resetPin'){
      if(isset($data['user_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $agents = $restModel->resetAgentPin($data['user_id']);
          if(count($agents) > 0){
            $sendMail = $restModel->sendWelcomeMail($agents['name'],$agents['email'],$agents['pin'],1);
            echo json_encode(["status"=>'success', "status_code"=>"200", 'user'=>$agents,"message"=>"Confirmation mail sent successfully."]);
          } else {
            echo json_encode(["status"=>'error', "status_code"=>"402", 'user'=>$agents,"message"=>"Confirmation mail not sent."]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }
    
    if($data['service_name']=='getBrands'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $brands = $restModel->getBrands();
          echo json_encode(["status"=>'success', "status_code"=>"200", 'brands'=>$brands]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getModels'){
      if(isset($data['make_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $models = $restModel->getModels($data['make_id']);
          echo json_encode(["status"=>'success', "status_code"=>"200", 'models'=>$models]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getVariantYears'){
      if(isset($data['variant_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $variantYears = $restModel->getVariantYears($data['variant_id']);
          echo json_encode(["status"=>'success', "status_code"=>"200", 'variantYears'=>$variantYears]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getModelVariants'){
      if(isset($data['model_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $modelVariants = $restModel->getModelVariants($data['model_id']);
          echo json_encode(["status"=>'success', "status_code"=>"200", 'modelVariants'=>$modelVariants]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='addQuotations'){
      if(isset($data['make_id']) &&  isset($data['make_display']) && isset($data['model_id']) && isset($data['model_display']) &&  isset($data['year_id']) &&  isset($data['year']) &&  isset($data['variant_id']) &&  isset($data['variant_display']) && isset($data['car_color']) && isset($data['fuel_type']) && isset($data['car_kms']) && isset($data['car_owner']) && isset($data['is_replacement']) && isset($data['structural_damage']) && isset($data['structural_damage_desc']) && isset($data['insurance_date']) && isset($data['refurbishment_cost']) && isset($data['requested_price']) && isset($data['recipient_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $insertID = $restModel->addQuotations($user['id'], $data['make_id'],$data['make_display'], $data['model_id'], $data['model_display'], $data['year_id'], $data['year'], $data['variant_id'], $data['variant_display'],$data['car_color'],$data['fuel_type'],$data['car_kms'],$data['car_owner'],$data['is_replacement'],$data['structural_damage'],$data['structural_damage_desc'],$data['insurance_date'],$data['refurbishment_cost'],$data['requested_price'],$data['recipient_id']);
            if($insertID!=''){
              echo json_encode(["status"=>"success", "status_code"=>"200","quotation_id"=>$insertID, "message"=>"Quotation request sent successfully."]);
            } else {
              echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Quotation request not sent."]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }


    if($data['service_name']=='editQuotation'){
      if(isset($data['quotation_id']) && isset($data['make_id']) &&  isset($data['make_display']) && isset($data['model_id']) && isset($data['model_display']) &&  isset($data['year_id']) &&  isset($data['year']) &&  isset($data['variant_id']) &&  isset($data['variant_display']) && isset($data['car_color']) && isset($data['fuel_type']) && isset($data['car_kms']) && isset($data['car_owner']) && isset($data['is_replacement']) && isset($data['structural_damage']) && isset($data['structural_damage_desc']) && isset($data['insurance_date']) && isset($data['refurbishment_cost']) && isset($data['requested_price']) && isset($data['recipient_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->editQuotation($data['quotation_id'], $user['id'], $data['make_id'],$data['make_display'], $data['model_id'], $data['model_display'], $data['year_id'], $data['year'], $data['variant_id'], $data['variant_display'],$data['car_color'],$data['fuel_type'],$data['car_kms'],$data['car_owner'],$data['is_replacement'],$data['structural_damage'],$data['structural_damage_desc'],$data['insurance_date'],$data['refurbishment_cost'],$data['requested_price'],$data['recipient_id']);
            if($updateCount > 0){
              echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Quotation updated."]);
            } else {
              echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Updation faild"]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='approveQuotation'){
      if(isset($data['quotation_id']) && isset($data['approved_price']) && isset($data['recipient_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->approveQuotation($data['quotation_id'],$data['approved_price'],$user['id'],$data['recipient_id']);
            if($updateCount > 0){
              echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Quotation approved."]);
            } else {
              echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Approval faild"]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='resubmitQuotation'){
      if(isset($data['quotation_id']) && isset($data['make_id']) &&  isset($data['make_display']) && isset($data['model_id']) && isset($data['model_display']) &&  isset($data['year_id']) &&  isset($data['year']) &&  isset($data['variant_id']) &&  isset($data['variant_display']) && isset($data['car_color']) && isset($data['fuel_type']) && isset($data['car_kms']) && isset($data['car_owner']) && isset($data['is_replacement']) && isset($data['structural_damage']) && isset($data['structural_damage_desc']) && isset($data['insurance_date']) && isset($data['refurbishment_cost']) && isset($data['requested_price']) && isset($data['recipient_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->resubmitQuotation($data['quotation_id'], $user['id'], $data['make_id'],$data['make_display'], $data['model_id'], $data['model_display'], $data['year_id'], $data['year'], $data['variant_id'], $data['variant_display'],$data['car_color'],$data['fuel_type'],$data['car_kms'],$data['car_owner'],$data['is_replacement'],$data['structural_damage'],$data['structural_damage_desc'],$data['insurance_date'],$data['refurbishment_cost'],$data['requested_price'],$data['recipient_id']);
            if($updateCount > 0){
              echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Quotation resubmitted."]);
            } else {
              echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Resubmission faild"]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='rejectQuotation'){
      if(isset($data['quotation_id']) && isset($data['token']) && isset($data['recipient_id']) && isset($data['reason'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->rejectQuotation($data['quotation_id'],$user['id'],$data['reason'],$data['recipient_id']);
            if($updateCount > 0){
              echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Quotation rejected."]);
            } else {
              echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"rejection failed"]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='soldQuotation'){
      if(isset($data['quotation_id']) && isset($data['recipient_id']) && isset($data['token']) && isset($data['reason'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->soldQuotation($data['quotation_id'],$user['id'],$data['reason'],$data['recipient_id']);
            if($updateCount > 0){
              echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Quotation sold."]);
            } else {
              echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"rejection failed"]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getNotifications'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $notifications = $restModel->getNotifications($user['id']);
            echo json_encode(["status"=>'success', "status_code"=>"200", 'notifications'=>$notifications]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getNotificationsCount'){
      if(isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $notifications = $restModel->getNotificationsCount($user['id']);
            if(count($notifications)>0){
              echo json_encode(["status"=>'success', "status_code"=>"200", 'notifications_count'=>$notifications[0]['notifications_count']]);
            } else {
              echo json_encode(["status"=>'success', "status_code"=>"200", 'notifications_count'=>0]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getAllQuotations'){
      if(isset($data['token'])){
        $sort_by='';
        $date_from='';
        $date_to='';
        $price_min='';
        $price_max='';
        if(isset($data['sort_by']) && $data['sort_by']!='' && in_array($data['sort_by'], array("ASC","DESC"))){
          $sort_by = $data['sort_by'];
        }
        if(isset($data['date_from']) && $data['date_from']!=''){
          $date_from = $data['date_from'];
        }
        if(isset($data['date_to']) && $data['date_to']!=''){
          $date_to = $data['date_to'];
        }
        if(isset($data['price_min']) && $data['price_min']!=''){
          $price_min = $data['price_min'];
        }
        if(isset($data['price_max']) && $data['price_max']!=''){
          $price_max = $data['price_max'];
        }
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            if($user['role']=='admin'){
              $allQuotations = $restModel->getAllQuotations('',$sort_by,$date_from,$date_to,$price_min,$price_max);
            } else {
              $allQuotations = $restModel->getAllQuotations($user['id'],$sort_by,$date_from,$date_to,$price_min,$price_max);
            }
            echo json_encode(["status"=>'success', "status_code"=>"200", 'quotations'=>$allQuotations]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getQuotationDetail'){
      if(isset($data['token']) && isset($data['quotation_id'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $quotation = $restModel->getQuotationDetail($data['quotation_id']);
          echo json_encode(["status"=>'success', "status_code"=>"200", 'quotation'=>$quotation]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getQuotationsCount'){
      if(isset($data['token']) && isset($data['user_id'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $quotation = $restModel->getQuotationsCount($data['user_id']);
          echo json_encode(["status"=>'success', "status_code"=>"200", 'quotation_details'=>$quotation]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='updateProfile'){
      if(isset($data['name']) && isset($data['company']) && isset($data['location']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $updateCount = $restModel->updateProfile($data['name'], $data['company'], $data['location'], $user['id']);
            echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Profile updated successfully."]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='addComments'){
      if(isset($data['quotation_id']) && isset($data['comments']) && isset($data['recipient_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $user = $restModel->getUserByToken($data['token']);
          if(count($user) > 0){
            $insertFlag = $restModel->addQuotationComments($user['id'], $data['quotation_id'], $data['comments'], $data['recipient_id']);
            if($insertFlag){
              echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Quotation comments added successfully."]);
            } else {
              echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Quotation comments not added."]);
            }
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='editComments'){
      if(isset($data['comment_id']) && isset($data['comments']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $updateCount = $restModel->updateQuotationComments($data['comment_id'], $data['comments']);
          if($updateCount > 0){
            echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Quotation comments updated successfully."]);
          } else {
            echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Quotation comments not updated"]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='deleteComments'){
      if(isset($data['comment_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $updateCount = $restModel->deleteQuotationComments($data['comment_id']);
          if($updateCount > 0){
            echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Quotation comments deleted successfully."]);
          } else {
            echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Quotation comments not deleted"]);
          }
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='getComments'){
      if(isset($data['quotation_id']) && isset($data['token'])){
        $restModel = new RESTAPIModel();
        $tokenValidation = $restModel->validateUserToken($data['token']);
        if($tokenValidation || ($tokenValidation==1)){
          $allComments = $restModel->getAllQuotationComments($data['quotation_id']);
          echo json_encode(["status"=>'success', "status_code"=>"200", 'comments'=>$allComments]);
        } else {
          echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }


    if($data['service_name']=='addQuotationImage'){
      if(isset($data['image']) && isset($data['quotation_id']) && isset($data['image_index']) && isset($data['token'])){
        $image_parts = explode(";base64,", $data['image']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = "image_". uniqid() . '.png';
        file_put_contents('./uploads/'.$file, $image_base64);
        if($file!=''){
          $restModel = new RESTAPIModel();
          $tokenValidation = $restModel->validateUserToken($data['token']);
          if($tokenValidation || ($tokenValidation==1)){
            $user = $restModel->getUserByToken($data['token']);
            if(count($user) > 0){
              $insertID = $restModel->addQuotationImages($data['quotation_id'], $file,$data['image_index']);
              if($insertID!=''){
                echo json_encode(["status"=>"success", "status_code"=>"200","image_id"=>$insertID, "message"=>"Quotation image uploaded successfully."]);
              } else {
                echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Quotation image not uploaded."]);
              }
            }
          } else {
            echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
          }
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($data['service_name']=='updateQuotationImage'){
      if(isset($data['image']) && isset($data['image_index']) && isset($data['image_id']) && isset($data['token'])){
        $image_parts = explode(";base64,", $data['image']);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = "image_". uniqid() . '.png';
        file_put_contents('./uploads/'.$file, $image_base64);
        if($file!=''){
          $restModel = new RESTAPIModel();
          $tokenValidation = $restModel->validateUserToken($data['token']);
          if($tokenValidation || ($tokenValidation==1)){
            $user = $restModel->getUserByToken($data['token']);
            if(count($user) > 0){
              $updateCount = $restModel->updateQuotationImages($data['image_id'], $file,$data['image_index']);
              if($updateCount > 0){
                echo json_encode(["status"=>"success", "status_code"=>"200","image_id"=>$data['image_id'], "message"=>"Quotation image updated successfully."]);
              } else {
                echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Quotation image not updated"]);
              }
            }
          } else {
            echo json_encode(["status"=>"error", "status_code"=>"401", "message"=>"Invalid Token"]);
          }
        }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

  } else {
    echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Web service not available"]);
  }
}

if($_SERVER['REQUEST_METHOD']=="GET")
{

  $allowedAPIs = array("getUserInfo","uploadImage","sendPush","importCSV","resetPin");


  if(isset($_GET['service_name']) && $_GET['service_name']!='' && in_array($_GET['service_name'], $allowedAPIs)){
    //Write action to txt log
    $log  = "User: ".$_SERVER['REMOTE_ADDR'].' - '.date("F j, Y, g:i a").PHP_EOL.
    "service_name: ".$_GET['service_name'].PHP_EOL.
    "filename: ".$_FILES['file']['name'].PHP_EOL.
    "-------------------------".PHP_EOL;
    //-
    file_put_contents('./log_'.date("j.n.Y").'.txt', $log, FILE_APPEND);
    if($_GET['service_name']=='uploadImage'){
    
      $target_path = "uploads/";
    
      $target_path = $target_path . basename( $_FILES['file']['name']);
      
      if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path)) {
        echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"Upload and move success"]);
      } else {
        echo json_encode(["status"=>"success", "status_code"=>"200","target_path"=>$target_path, "message"=>"There was an error uploading the file, please try again!"]);
      }
      
    }


    if($_GET['service_name']=='resetPin'){
      if(isset($_GET['user_id'])){
        $restModel = new RESTAPIModel();
          $agents = $restModel->resetAgentPin($_GET['user_id']);
          if(count($agents) > 0){
            // $sendMail = $restModel->sendWelcomeMail($agents['name'],$agents['email'],$agents['pin'],1);
            echo json_encode(["status"=>'success', "status_code"=>"200", 'user'=>$agents]);
          } else {
            echo json_encode(["status"=>'error', "status_code"=>"402", "message"=>"Reset failed."]);
          }
      } else {
        echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Invalid parameters"]);
      }
    }

    if($_GET['service_name']=='sendPush'){
      $restModel = new RESTAPIModel();
      $restModel->sendSinglePush('Notification','This is test message from server');
    }

    if($_GET['service_name']=='importCSV'){
      $restModel = new RESTAPIModel();
      // $status = $restModel->importDataFromCSV();
      // if($status=='success'){
      //   echo json_encode(["status"=>"success", "status_code"=>"200", "message"=>"CSV Data Imported into the Database"]);
      // } else {
      //   echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Problem in Importing CSV Data"]);
      // }
      // $makemodels = $restModel->getallMakeModelData();
      // $sendMail = $restModel->sendWelcomeMail('Vinoth','orvinothkumar@gmail.com');
      
    }

    
    if($_GET['service_name']=='getUserInfo'){
      if(isset($_GET['id']))
      {
        $id =  $_GET['id'];
        $restModel = new RESTAPIModel();
        $json = $restModel->get_single_user_info($id);
        if(empty($json))
        header("HTTP/1.1 404 Not Found");
        echo json_encode($json);
      } else{
        $restModel = new RESTAPIModel();
        $json = $restModel->get_all_user_list();
        if(empty($json))
        header("HTTP/1.1 404 Not Found");
        echo json_encode($json);
      }
    }
  } else {
    echo json_encode(["status"=>"error","status_code"=>"402", "message"=>"Web service not available"]);
  }
  
}

?>