<?php
    require_once('database.php');
    require_once('Firebase.php');
    require_once('Push.php');
    require_once('mailer/class.phpmailer.php');
    Class RESTAPIModel{

        function generateUniquePIN(){
            $regenerateNumber = true;
            do {
                $regNum      = rand(1000,9999);
                $checkRegNum = "SELECT * FROM users WHERE password = '$regNum'";
                $result      = mysqli_query($connection, $checkRegNum);
                if (mysqli_num_rows($result) == 0) {
                    $regenerateNumber = false;
                }
            } while ($regenerateNumber);
            return $regNum;
        }
        function get_all_user_list()
        {
            $users = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT * FROM users");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $users[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $users;
        }

        function get_single_user_info($id){
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE id = '$id'");
                $users = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $users;
        }

        function getUserByMobile($mobile){
            $count  = 0;
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE mobile = '$mobile' AND active='1'");
                $count  = mysqli_num_rows($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function updateOTP($otp,$mobile){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "UPDATE users SET otp = '".$otp."', is_expired = 0 WHERE mobile = '" . $mobile . "'");
                $count  = mysqli_affected_rows($conn);
                
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function verifyOTP($otp,$mobile){
            $user = null;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT * FROM users WHERE mobile='".$mobile."' AND otp='" . $otp . "' AND is_expired!=1 AND active='1'");
                $user = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $user;
        }

        function validateMobileNumber($mobile1,$mobile2=''){
            $user = null;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT mobile FROM users WHERE (mobile='".$mobile1."' OR mobile='".$mobile2."') AND active='1'");
                $user = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $user;
        }

        function validatePin($pin){
            $user = null;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT * FROM users WHERE (password='".$pin."') AND active='1' AND ((role='agent' AND is_expired='0') OR (role='admin') OR (id='2'))");
                $user = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $user;
        }

        function updateToken($otp,$hashed_password,$push_token){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "UPDATE users SET is_expired = 1, token='".$hashed_password."', push_token='".$push_token."' WHERE otp = '" . $otp . "'");
                $count  = mysqli_affected_rows($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }


        function updateTokenByPin($pin,$hashed_password,$push_token,$device_type){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "UPDATE users SET is_expired = 1, token='".$hashed_password."', push_token='".$push_token."', device_type='".$device_type."' WHERE password = '" . $pin . "'");
                $count  = mysqli_affected_rows($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function validateUserToken($token){
            $count  = 0;
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE token = '$token' AND active='1'");
                $count  = mysqli_num_rows($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            if($count > 0){
                return true;
            } else {
                return false;
            }
        }

        function getUserByToken($token){
            $user  = [];
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT id,role FROM users WHERE token = '$token' AND active='1'");
                $user = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
           return $user;
        }

        function validateEmail($email){
            $count  = 0;
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE email = '$email'");
                $count  = mysqli_num_rows($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            if($count > 0){
                return true;
            } else {
                return false;
            }
        }

        function validateMobile($mobile){
            $count  = 0;
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE mobile = '$mobile'");
                $count  = mysqli_num_rows($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            if($count > 0){
                return true;
            } else {
                return false;
            }
        }

        function addAgent($name, $mobile, $email='', $company='', $location='', $designation='', $pin='',$added_by='0'){
            
            $insertFlag  = false;
            try {
                $Dbobj = new DbConnection(); 
                $sql = "INSERT INTO users ( name, mobile, email, company, location, designation, password, role,added_by, created_on ) VALUES ('$name', '$mobile', '$email', '$company', '$location', '$designation', '$pin', 'agent', '$added_by', NOW())";
                $query = mysqli_query($Dbobj->getdbconnect(), $sql);
                $insertFlag  = $query;
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $insertFlag;
        }

        function addStudent($name,$registration_no, $mobile, $email){
            
            $insertFlag  = false;
            try {
                $Dbobj = new DbConnection(); 
                $sql = "INSERT INTO student ( name, registration_no, phone, email ) VALUES ('$name', '$registration_no', '$mobile', '$email')";
                $query = mysqli_query($Dbobj->getdbconnect(), $sql);
                $insertFlag  = $query;
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $insertFlag;
        }

        function updateAgent($name, $mobile, $email="", $company="", $location="", $designation="", $id){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE users SET name = '".$name."', mobile =  '".$mobile."', email =  '".$email."', company =  '".$company."', location =  '".$location."', designation =  '".$designation."' WHERE id = '" . $id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function updateProfile($name, $company="", $location="",$id){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE users SET name = '".$name."', company =  '".$company."', location =  '".$location."' WHERE id = '" . $id . "'";
                $query = mysqli_query($conn, $sql);
                $count = mysqli_affected_rows($conn);
                
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function updateStatusOfAgent($status,$id){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE users SET active = '".$status."' WHERE id = '" . $id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function deleteAgent($id){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE users SET active = '2' WHERE id = '" . $id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function getAllAgentsList(){
            $agents = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT id,name,mobile,email,company,location,designation,password as pin,is_expired,active FROM users WHERE role='agent' ORDER BY active DESC");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $agents[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $agents;
        }

        function getNotifications($recipient_id=''){
            $notifications = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT n.sender_id, n.quotation_id,s.name as sender_name,n.type,n.title,n.message, IFNULL(n.description,'') as description,n.recipient_id,r.name as recipient_name,n.is_unread,n.created_on FROM notifications n INNER JOIN users s ON n.sender_id = s.id INNER JOIN users r ON n.recipient_id = r.id WHERE n.recipient_id = '".$recipient_id."' ORDER BY n.created_on DESC");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        
                        $row['created_on'] = $this->time_elapsed_string($row['created_on']);
                        $notifications[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $notifications;
        }

        function getNotificationsCount($recipient_id=''){
            $notifications = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT COUNT(*) as notifications_count FROM notifications WHERE recipient_id = '".$recipient_id."'");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $notifications[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $notifications;
        }

        function resetAgentPin($id){
            $user = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $pin = $this->generateUniquePIN();
                $sql = "UPDATE users SET password = '".$pin."', token = '', is_expired = '0' WHERE id = '" . $id . "' AND role='agent' AND id!='2' AND active!='2'";
                $updateQuery = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                $query = mysqli_query($conn, "SELECT name,email,mobile,password as pin,token,otp FROM users WHERE id='".$id."' AND active!='2'");
                $user = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $user;
        }

        function getBrands(){
            $brands = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT make_id,make_name,make_display,logo, CASE WHEN is_popular = '1' THEN 'yes' ELSE 'no' END AS is_popular, display_order, status FROM car_make WHERE status='1' ORDER BY display_order DESC");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $brands[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $brands;
        }

        function getModels($make_id){
            $models = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT model_id,make_id,model_name,model_display, CASE WHEN is_popular = '1' THEN 'yes' ELSE 'no' END AS is_popular, display_order, status FROM car_model WHERE make_id='".$make_id."' AND status='1' ORDER BY display_order DESC");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $models[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $models;
        }

        function getModelVariants($model_id){
            $modelVariants = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT * FROM car_variant WHERE model_id='".$model_id."' AND status='1' ORDER BY display_order DESC");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $modelVariants[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $modelVariants;
        }

        function getVariantYears($variant_id){
            $variantYears = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT * FROM car_year WHERE variant_id='".$variant_id."' AND status='1' ORDER BY display_order DESC");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $variantYears[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $variantYears;
        }

        function getPushTokenByUserID($sender_id,$recipient_id){
            $user  = [];
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT (SELECT name FROM users WHERE id = '$sender_id' LIMIT 1) as name,push_token FROM users WHERE id = '$recipient_id' AND active='1'");
                $user = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
           return $user;
        }

        function addNotifications($sender_id,$quotation_id, $type, $title, $message, $recipient_id='', $description=''){
            $$last_id  = '';
            try {
                $Dbobj = new DbConnection(); 
                $conn = $Dbobj->getdbconnect();
                $sql = "INSERT INTO notifications ( sender_id, quotation_id, type, title, message,description, recipient_id,created_on ) VALUES ('$sender_id', '$quotation_id', '$type', '$title', '$message', '$description', '$recipient_id',NOW())";
                $query = mysqli_query($conn, $sql);
                $last_id = mysqli_insert_id($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $last_id;
        }

        function addQuotations($user_id, $make_id, $make_display, $model_id, $model_display, $year_id, $year, $variant_id, $variant_display, $car_color='',$fuel_type='',$car_kms='',$car_owner='',$is_replacement='',$structural_damage='',$structural_damage_desc='',$insurance_date='',$refurbishment_cost='',$requested_price='',$recipient_id='0',$priority){
            
            $$last_id  = '';
            try {
                $Dbobj = new DbConnection(); 
                $conn = $Dbobj->getdbconnect();
                $sql = "INSERT INTO quotations ( user_id, make_id, make_display, model_id, model_display, year_id, year, variant_id, variant_display, car_color,fuel_type,car_kms,car_owner,is_replacement,structural_damage,structural_damage_desc,insurance_date,refurbishment_cost,requested_price,created_on,priority ) VALUES ('$user_id', '$make_id', '$make_display', '$model_id', '$model_display', '$year_id', '$year', '$variant_id', '$variant_display','$car_color','$fuel_type','$car_kms','$car_owner','$is_replacement','$structural_damage','$structural_damage_desc','$insurance_date','$refurbishment_cost','$requested_price',NOW(),'$priority')";
                $query = mysqli_query($conn, $sql);
                $last_id = mysqli_insert_id($conn);
                if($last_id>0){
                    $user = $this->getPushTokenByUserID($user_id,$recipient_id);
                    if(count($user)>0){
                        $title = "Quotation Request";
                        $message = "New quotation(ID#:".$last_id.") has been requested by ".$user['name'];
                        $addNotify = $this->addNotifications($user_id, $last_id, 'quotation', $title, $message, $recipient_id);
                        $this->sendSinglePush($title, $message,'',$user['push_token'],$user['device_type']);
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $last_id;
        }


        function editQuotation($quotation_id, $user_id, $make_id, $make_display, $model_id, $model_display, $year_id, $year, $variant_id, $variant_display, $car_color='',$fuel_type='',$car_kms='',$car_owner='',$is_replacement='',$structural_damage='',$structural_damage_desc='',$insurance_date='',$refurbishment_cost='',$requested_price='',$recipient_id='0',$priority=""){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $currentDate = date("Y/m/d");
                $sql = "UPDATE quotations SET make_id='".$make_id."', make_display='".$make_display."', model_id='".$model_id."', model_display='".$model_display."', year_id='".$year_id."', year='".$year."', variant_id='".$variant_id."', variant_display='".$variant_display."', car_color='".$car_color."',fuel_type='".$fuel_type."',car_kms='".$car_kms."',car_owner='".$car_owner."',is_replacement='".$is_replacement."',structural_damage='".$structural_damage."',structural_damage_desc='".$structural_damage_desc."',insurance_date='".$insurance_date."',refurbishment_cost='".$refurbishment_cost."',requested_price='".$requested_price."',priority='".$priority."' WHERE id = '" . $quotation_id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                if($count>0){
                    $user = $this->getPushTokenByUserID($user_id,$recipient_id);
                    if(count($user)>0){
                        $title = "Quotation Updation";
                        $message = "Quotation (ID#:".$quotation_id.") has been updated by ".$user['name'];
                        $addNotify = $this->addNotifications($user_id, $quotation_id, 'quotation', $title, $message, $recipient_id);
                        $this->sendSinglePush($title, $message,'',$user['push_token'],$user['device_type']);
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function resubmitQuotation($quotation_id, $user_id, $make_id, $make_display, $model_id, $model_display, $year_id, $year, $variant_id, $variant_display, $car_color='',$fuel_type='',$car_kms='',$car_owner='',$is_replacement='',$structural_damage='',$structural_damage_desc='',$insurance_date='',$refurbishment_cost='',$requested_price='',$recipient_id='0'){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $currentDate = date("Y/m/d");
                $sql = "UPDATE quotations SET make_id='".$make_id."', make_display='".$make_display."', model_id='".$model_id."', model_display='".$model_display."', year_id='".$year_id."', year='".$year."', variant_id='".$variant_id."', variant_display='".$variant_display."', car_color='".$car_color."',fuel_type='".$fuel_type."',car_kms='".$car_kms."',car_owner='".$car_owner."',is_replacement='".$is_replacement."',structural_damage='".$structural_damage."',structural_damage_desc='".$structural_damage_desc."',insurance_date='".$insurance_date."',refurbishment_cost='".$refurbishment_cost."',requested_price='".$requested_price."',approved_price='0', approved_by='0', approved_date=NULL, dropped_by='0', dropped_date=NULL, reason = '', status = '0' WHERE id = '" . $quotation_id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                if($count>0){
                    $user = $this->getPushTokenByUserID($user_id,$recipient_id);
                    if(count($user)>0){
                        $title = "Quotation Resubmit";
                        $message = "Quotation (ID#:".$quotation_id.") has been resubmitted with new price ".$requested_price." by ".$user['name'];
                        $addNotify = $this->addNotifications($user_id, $quotation_id, 'quotation', $title, $message, $recipient_id);
                        $this->sendSinglePush($title, $message,'',$user['push_token'],$user['device_type']);
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function changePriority($quotation_id,$priority="",$approved_by=0,$recipient_id='0'){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $currentDate = date("Y/m/d");
                $sql = "UPDATE quotations SET priority='".$priority."' WHERE id = '" . $quotation_id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                if($count>0){
                    $user = $this->getPushTokenByUserID($approved_by,$recipient_id);
                    if(count($user)>0){
                        $title = "Priority Changed";
                        $message = "Quotation (ID#:".$quotation_id.") has been marked as ".$priority." by ".$user['name'];
                        $addNotify = $this->addNotifications($approved_by, $quotation_id, 'quotation', $title, $message, $recipient_id);
                        $this->sendSinglePush($title, $message,'',$user['push_token'],$user['device_type']);
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function approveQuotation($quotation_id,$approved_price,$approved_by=0,$recipient_id='0'){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $currentDate = date("Y/m/d");
                $sql = "UPDATE quotations SET approved_price='".$approved_price."', approved_by='".$approved_by."', approved_date=DATE('".$currentDate."'), status = '1' WHERE id = '" . $quotation_id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                if($count>0){
                    $user = $this->getPushTokenByUserID($approved_by,$recipient_id);
                    if(count($user)>0){
                        $title = "Quotation Approval";
                        $message = "Quotation (ID#:".$quotation_id.") has been approved by ".$user['name'];
                        $addNotify = $this->addNotifications($approved_by, $quotation_id, 'quotation', $title, $message, $recipient_id);
                        $this->sendSinglePush($title, $message,'',$user['push_token'],$user['device_type']);
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function rejectQuotation($quotation_id,$dropped_by=0,$reason='',$recipient_id='0'){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $currentDate = date("Y/m/d");
                $sql = "UPDATE quotations SET dropped_by='".$dropped_by."', dropped_date=DATE('".$currentDate."'), reason = '".$reason."', status = '2' WHERE id = '" . $quotation_id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                if($count>0){
                    $user = $this->getPushTokenByUserID($dropped_by,$recipient_id);
                    if(count($user)>0){                    
                        $title = "Quotation Rejection";
                        $message = "Quotation: (ID#:".$quotation_id.") has been rejected by ".$user['name'];
                        $addNotify = $this->addNotifications($dropped_by, $quotation_id, 'quotation', $title, $message, $recipient_id);
                        $this->sendSinglePush($title, $message,'',$user['push_token'],$user['device_type']);
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function soldQuotation($quotation_id,$dropped_by=0,$reason='',$recipient_id='0'){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $currentDate = date("Y/m/d");
                $sql = "UPDATE quotations SET dropped_by='".$dropped_by."', dropped_date=DATE('".$currentDate."'), reason = '".$reason."', status = '2' WHERE id = '" . $quotation_id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                if($count>0){
                    $user = $this->getPushTokenByUserID($dropped_by,$recipient_id);    
                    if(count($user)>0){                
                        $title = "Quotation Sold";
                        $message = "Quotation: (ID#:".$quotation_id.") has been marked as sold by ".$user['name'];
                        $addNotify = $this->addNotifications($dropped_by, $quotation_id, 'quotation', $title, $message, $recipient_id);
                        $this->sendSinglePush($title, $message,'',$user['push_token'],$user['device_type']);
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function getAllQuotations($user_id='',$sort_by='',$date_from='',$date_to='',$price_min='',$price_max=''){
            $quotations = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $condition = '';
                if($user_id != ''){
                    $condition .= " AND q.user_id='".$user_id."'";
                }
                if($date_from != '' && $date_to != ''){
                    $condition .= " AND (DATE(q.created_on) between '".$date_from."' and '".$date_to."')";
                }
                if($price_min != '' && $price_max != ''){
                    $condition .= " AND (q.requested_price between '".$price_min."' and '".$price_max."')";
                }                
                if($sort_by != ''){
                    $condition .= " ORDER BY q.created_on ".$sort_by;
                } else {
                    $condition .= " ORDER BY q.created_on DESC";
                }
                $sql = "SELECT q.id, q.user_id, u.name,u.role,IFNULL(u.mobile,'') as mobile,u.company, q.make_id, q.make_display, q.model_id, q.model_display, q.year_id, q.year, q.variant_id, q.variant_display, q.car_color, q.fuel_type, q.car_kms, q.car_owner, q.is_replacement, q.structural_damage, q.structural_damage_desc, q.insurance_date, q.refurbishment_cost, q.requested_price, q.approved_price,IFNULL((SELECT au.name FROM users au WHERE au.id = q.approved_by LIMIT 1),'') as approved_by,IFNULL((SELECT au.role FROM users au WHERE au.id = q.approved_by LIMIT 1),'') as approved_by_role,DATE_FORMAT(q.approved_date, '%d %b %Y') as approved_date,DATE_FORMAT(q.approved_date, '%Y-%m-%d') as s_approved_date, IFNULL((SELECT du.name FROM users du WHERE du.id = q.dropped_by LIMIT 1),'') as dropped_by, IFNULL((SELECT du.role FROM users du WHERE du.id = q.dropped_by LIMIT 1),'') as dropped_by_role, DATE_FORMAT(q.dropped_date, '%d %b %Y') as dropped_date, DATE_FORMAT(q.dropped_date, '%Y-%m-%d') as s_dropped_date,reason, CASE WHEN (q.status = '0' AND (SELECT count(qc.id) FROM quotation_comments qc WHERE qc.quotation_id = q.id)=0) THEN 'New' WHEN (q.status = '0' AND (SELECT count(qc.id) FROM quotation_comments qc WHERE qc.quotation_id = q.id)>0) THEN 'Pending' WHEN q.status = '1' THEN 'Approved' ELSE 'Rejected' END AS status,DATE_FORMAT(q.created_on, '%d %b %Y') as created_date,DATE_FORMAT(q.created_on, '%Y-%m-%d') as s_created_date, q.priority FROM quotations q INNER JOIN users u ON q.user_id = u.id WHERE q.status IN (0,1,2)".$condition;
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        // $row['created_date'] = $this->time_elapsed_string($row['created_date']);
                        $images = $this->getQuotationImages($row['id']);
                        if(count($images)>0){
                            $row['images'] = $images;
                        } else {
                            $row['images'] = [];
                        }
                        $quotations[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $quotations;
        }

        function getQuotationImages($quotation_id){
            $images = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT id,image_path,image_index FROM quotation_images WHERE quotation_id = ".$quotation_id);
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_array($query)) {
                        $tempArr = [];
                        $tempArr['image_id'] = $row['id'];
                        $tempArr['image_path'] = UPLOAD_BASE_PATH.$row['image_path'];
                        $tempArr['image_index'] = $row['image_index'];
                        $images[] = $tempArr;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $images;
        }

        function getQuotationDetail($id){
            $quotation = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT q.id, q.user_id, u.name,u.role,IFNULL(u.mobile,'') as mobile,u.company, make_id, make_display, model_id, model_display, year_id, year, variant_id, variant_display, car_color, fuel_type, car_kms, car_owner, is_replacement,structural_damage,structural_damage_desc, insurance_date, refurbishment_cost, requested_price, approved_price,IFNULL((SELECT au.name FROM users au WHERE au.id = q.approved_by LIMIT 1),'') as approved_by,IFNULL((SELECT au.role FROM users au WHERE au.id = q.approved_by LIMIT 1),'') as approved_by_role,DATE_FORMAT(q.approved_date, '%d %b %Y') as approved_date, IFNULL((SELECT du.name FROM users du WHERE du.id = q.dropped_by LIMIT 1),'') as dropped_by, IFNULL((SELECT du.role FROM users du WHERE du.id = q.dropped_by LIMIT 1),'') as dropped_by_role,DATE_FORMAT(q.dropped_date, '%d %b %Y') as dropped_date,reason, CASE WHEN (q.status = '0' AND (SELECT count(qc.id) FROM quotation_comments qc WHERE qc.quotation_id = q.id)=0) THEN 'New' WHEN (q.status = '0' AND (SELECT count(qc.id) FROM quotation_comments qc WHERE qc.quotation_id = q.id)>0) THEN 'Pending' WHEN q.status = '1' THEN 'Approved' ELSE 'Rejected' END AS status,DATE_FORMAT(q.created_on, '%d %b %Y') as created_date, q.priority FROM quotations q INNER JOIN users u ON q.user_id = u.id WHERE q.id = ".$id);
                $quotation = mysqli_fetch_assoc($query);
                if(count($quotation)>0){
                    $images = $this->getQuotationImages($quotation['id']);
                    if(count($images)>0){
                        $quotation['images'] = $images;
                    } else {
                        $quotation['images'] = [];
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $quotation;
        }

        function getQuotationsCount($id){
            $quotation = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT COUNT(*) as total_quotations,IFNULL(sum(case when approved_by = '1' then 1 else 0 end),0) as approved_quotations,IFNULL(sum(case when dropped_by = '1' then 1 else 0 end),0) as admin_rejected_quotations,IFNULL(sum(case when dropped_by = '".$id."' then 1 else 0 end),0) as agent_rejected_quotations FROM quotations q WHERE q.user_id = ".$id);
                $quotation = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $quotation;
        }

        function addQuotationComments($user_id, $quotation_id, $comments, $recipient_id='0'){
            
            $insertFlag  = false;
            try {
                $Dbobj = new DbConnection(); 
                $conn = $Dbobj->getdbconnect();
                $sql = "INSERT INTO quotation_comments ( user_id, quotation_id, comments, created_on ) VALUES ('$user_id', '$quotation_id', '$comments', NOW())";
                $query = mysqli_query($conn, $sql);
                $insertFlag  = $query;
                $last_id = mysqli_insert_id($conn);
                if($last_id>0){
                    $user = $this->getPushTokenByUserID($user_id,$recipient_id);
                    $title = "Comment";
                    $message = "Quotation comment added by ".$user['name'];
                    $addNotify = $this->addNotifications($user_id, $quotation_id, 'comment', $title, $message, $recipient_id, $comments);
                    if(count($user)>0){
                        $this->sendSinglePush($title, $message,'',$user['push_token'],$user['device_type']);
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $insertFlag;
        }

        function updateQuotationComments($id, $comments){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE quotation_comments SET comments = '".$comments."' WHERE id = '" . $id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function deleteQuotationComments($id){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "DELETE FROM quotation_comments WHERE id = '" . $id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function getAllQuotationComments($id){
            $comments = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT qc.id, qc.user_id, u.role, u.name as user_name, qc.comments, qc.created_on as date_time FROM quotation_comments qc INNER JOIN users u ON qc.user_id = u.id WHERE qc.quotation_id = ".$id);
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $row['date_time'] = $this->time_elapsed_string($row['date_time']);
                        $comments[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $comments;
        }

        function time_elapsed_string($datetime, $full = false) {
            $now = new DateTime;
            $ago = new DateTime($datetime);
            $diff = $now->diff($ago);
        
            $diff->w = floor($diff->d / 7);
            $diff->d -= $diff->w * 7;
        
            $string = array(
                'y' => 'year',
                'm' => 'month',
                'w' => 'week',
                'd' => 'day',
                'h' => 'hour',
                'i' => 'minute',
                's' => 'second',
            );
            foreach ($string as $k => &$v) {
                if ($diff->$k) {
                    $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                } else {
                    unset($string[$k]);
                }
            }
        
            if (!$full) $string = array_slice($string, 0, 1);
            return $string ? implode(', ', $string) . ' ago' : 'just now';
        }

        function addQuotationImages($quotation_id, $image_path, $image_index=''){
            
            $last_id  = '';
            try {
                $Dbobj = new DbConnection(); 
                $conn = $Dbobj->getdbconnect();
                $sql = "INSERT INTO quotation_images ( quotation_id, image_path, image_index ) VALUES ('$quotation_id', '$image_path', '$image_index')";
                $query = mysqli_query($conn, $sql);
                $last_id = mysqli_insert_id($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $last_id;
        }

        function updateQuotationImages($image_id, $image_path, $image_index=''){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE quotation_images SET image_path = '".$image_path."',image_index = '".$image_index."' WHERE id = '" . $image_id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function sendSinglePush($title, $message, $imagePath='',$device_token,$device_type){
            if($device_type=='ios'){
                // Provide the Host Information.
                $tHost = "gateway.sandbox.push.apple.com";
                //$tHost = "gateway.push.apple.com";
                $tPort = 2195;
                // Provide the Certificate and Key Data.
                $tCert = "certificate.pem";
                $tPassphrase = "heeracars2020";
                $tToken = $device_token;
                // Audible Notification Option.
                $tSound = "default";
                // The content that is returned by the LiveCode "pushNotificationReceived" message.
                $tPayload = "APNS payload";
                // Create the message content that is to be sent to the device.
                // Create the payload body
                //Below code for non silent notification
                $tBody["aps"] = array(
                "badge" => +1,
                "alert" => array(
                    "title"=> $title,
                    "body"=> $message
                ),
                "sound" => "default"
                );
                $tBody ["payload"] = $tPayload;
                // Encode the body to JSON.
                $tBody = json_encode ($tBody);
                // Create the Socket Stream.
                $tContext = stream_context_create ();
                stream_context_set_option ($tContext, "ssl", "local_cert", $tCert);
                // Remove this line if you would like to enter the Private Key Passphrase manually.
                stream_context_set_option ($tContext, "ssl", "passphrase", $tPassphrase);
                // Open the Connection to the APNS Server.
                $tSocket = stream_socket_client ("ssl://".$tHost.":".$tPort, $error, $errstr, 30, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $tContext);
                // Check if we were able to open a socket.
                if (!$tSocket)
                exit ("APNS Connection Failed: $error $errstr" . PHP_EOL);
                // Build the Binary Notification.
                $tMsg = chr (0) . chr (0) . chr (32) . pack ("H*", $tToken) . pack ("n", strlen ($tBody)) . $tBody;
                // Send the Notification to the Server.
                $tResult = fwrite ($tSocket, $tMsg, strlen ($tMsg));
                if ($tResult)
                echo "Delivered Message to APNS" . PHP_EOL;
                else
                echo "Could not Deliver Message to APNS" . PHP_EOL;
                // Close the Connection to the Server.
                fclose ($tSocket);
            } else {
            //creating a new push for android
            $push = null; 
            //first check if the push has an image with it
            if($imagePath!=''){
                $push = new Push(
                        $title,
                        $message,
                        $imagePath
                    );
            }else{
                //if the push don't have an image give null in place of image
                $push = new Push(
                        $title,
                        $message,
                        null
                    );
            }

            //getting the push from push object
            $mPushNotification = $push->getPush();

            //getting the token from database object 
            $devicetoken = array($device_token);

            //creating firebase class object 
            $firebase = new Firebase(); 

            //sending push notification and displaying result 
            $firebase->send($devicetoken, $mPushNotification);
            }
        }

        function importDataFromCSV(){
            $Dbobj = new DbConnection();
            $conn = $Dbobj->getdbconnect();
            $fileName = "./csv/make_model.csv";
            $file = fopen($fileName, "r");
        
            while (($column = fgetcsv($file, 10000, ";")) !== FALSE) {
                $sqlInsert = "INSERT into make_model (vehicle,make,model,variant,year)
                    values ('" . $column[0] . "','" . $column[1] . "','" . $column[2] . "','" . $column[3] . "','" . $column[4] . "')";
                $result = mysqli_query($conn, $sqlInsert);
                
                if (!empty($result)) {
                    $type = "success";
                } else {
                    $type = "error";
                }
            }

            echo $type;
        }

        function getallMakeModelData(){
            $makemodels = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT vehicle,make,model,variant,year FROM make_model WHERE vehicle='CAR' AND variant NOT LIKE '%Select%' AND year NOT LIKE '%Select%'");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $makemodels[] = $row;
                    }
                }
                echo "<pre>";
                // print_r($makemodels);
                $make = '';
                for($i=0;$i<count($makemodels);$i++){
                    if($make!=$makemodels[$i]['make']){
                    $make = $makemodels[$i]['make'];
                    // echo "make =>".$i." ".$make."<br>";
                    $make_display = ucfirst($make);
                    echo "INSERT into car_make (make_name,make_display)
                    values ('" . $make . "','" . $make_display . "')"."<br>";
                    $sqlInsertMake = "INSERT into car_make (make_name,make_display)
                    values ('" . $make . "','" . $make_display . "')";
                    $queryMake = mysqli_query($conn, $sqlInsertMake);
                    $make_last_id = mysqli_insert_id($conn);
                    $model = '';
                    for($j=0;$j<count($makemodels);$j++){
                        if($make==$makemodels[$j]['make'] && $model!=$makemodels[$j]['model']){
                        $model = $makemodels[$j]['model'];
                        // echo "model =>".$j." ".$model."<br>";
                        $model_display = ucfirst($model);
                        echo "INSERT into car_model (make_id,model_name,model_display)
                        values ($make_last_id,'" . $model . "','" . $model_display . "')"."<br>";
                        $sqlInsertModel = "INSERT into car_model (make_id,model_name,model_display)
                        values ($make_last_id,'" . $model . "','" . $model_display . "')";
                        $queryModel = mysqli_query($conn, $sqlInsertModel);
                        $model_last_id = mysqli_insert_id($conn);
                        $variant = '';
                        for($k=0;$k<count($makemodels);$k++){
                            if($make==$makemodels[$k]['make'] && $model==$makemodels[$k]['model'] && $variant!=$makemodels[$k]['variant']){
                            $variant = $makemodels[$k]['variant'];
                            // echo "variant =>".$k." ".$variant."<br>";
                            $variant_display = ucfirst($variant);
                            echo "INSERT into car_variant (model_id,variant_name,variant_display)
                            values ($model_last_id,'" . $variant . "','" . $variant_display . "')"."<br>";
                            $sqlInsertVariant = "INSERT into car_variant (model_id,variant_name,variant_display)
                            values ($model_last_id,'" . $variant . "','" . $variant_display . "')";
                            $queryVariant = mysqli_query($conn, $sqlInsertVariant);
                            $variant_last_id = mysqli_insert_id($conn);
                            $year = '';
                            for($l=0;$l<count($makemodels);$l++){
                                if($make==$makemodels[$l]['make'] && $model==$makemodels[$l]['model'] && $variant==$makemodels[$l]['variant'] && $year!=$makemodels[$l]['year']){
                                $year = $makemodels[$l]['year'];
                                // echo "year =>".$l." ".$year."<br>";
                                $str = $year;
                                $expArr = (explode("-",$str));
                                $start = $expArr[0];
                                $end = ($expArr[1]=='Present')?"2020":$expArr[1];
                                for($m=($start);$m<=$end;$m++){
                                // echo $m."<br>";
                                echo  "INSERT into car_year (variant_id,year_display)
                                values ($variant_last_id,'" . $m . "')"."<br>";
                                $sqlInsertYear = "INSERT into car_year (variant_id,year_display)
                                values ($variant_last_id,'" . $m . "')";
                                $queryVariant = mysqli_query($conn, $sqlInsertYear);
                                $year_last_id = mysqli_insert_id($conn);
                                echo $year_last_id." <br>";
                                }
                                }    
                            }
                            }    
                        }
                        }
                    }
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $makemodels;
        }

        function sendWelcomeMail($name,$toMail='',$pin='',$resetFlag=0){
            if($toMail!=''){
                $mail = new PHPMailer(true);
                $full_name  = strip_tags($name);
                $ConditionalmailContent = '';
                $ConditionalMailSubject = '';
                if($resetFlag==0){
                    $ConditionalMailSubject = "Heera Cars - Your Account has been created successfully!";
                    $ConditionalmailContent = '<tr>
                                                <td colspan="4" style="padding:15px;">
                                                    <p style="font-size: 20px;text-align: center;"><b>Welcome to Heera Cars! </b></p>
                                                    <p style="font-size:15px">Hi '.$full_name.',</p>
                                                    <p style="font-size:15px">Your account has now been created and you can log in by using secret pin : '.$pin.' in our mobile app and start using our services.</p>
                                                    <p style="font-size:15px">Here\'s the link to download the Heera Cars app.</p>
                                                    <p style="font-size:15px"><a href="https://play.google.com/store/apps/details?id=com.heeracars" target="_blank">https://play.google.com/store/apps/details?id=com.heeracars</a></p>
                                                    <p style="font-size:15px;margin: 0;">Thanks</p>
                                                <p style="font-size:15px;margin: 5px 0;">Team Heera Cars</p>
                                                </td>
                                            </tr>';
                } else {
                    $ConditionalMailSubject = "Heera Cars - Your Account has been reset successfully!";
                    $ConditionalmailContent = '<tr>
                                                <td colspan="4" style="padding:15px;">
                                                    <p style="font-size:15px">Hi '.$full_name.',</p>
                                                    <p style="font-size:15px">Your account has been reset and you can log in by using new secret pin : '.$pin.'.</p>
                                                    <p style="font-size:15px;margin: 0;">Thanks</p>
                                                <p style="font-size:15px;margin: 5px 0;">Team Heera Cars</p>
                                                </td>
                                            </tr>';
                }
                $message  = "<html><body>";

                $message .= "<table width='100%' bgcolor='#e0e0e0' cellpadding='0' cellspacing='0' border='0'>";

                $message .= "<tr><td>";

                $message .= '<table align="center" width="100%" border="0" cellpadding="0" cellspacing="0" style="max-width:650px;background-color:#fff;font-family:Verdana,Geneva,sans-serif"><thead>
                            <tr height="80">
                                <th colspan="4" style="background-color: #fff;border-bottom: none;font-family:Verdana,Geneva,sans-serif;color:#333;font-size:34px;padding: 10px;">
                                </th>
                            </tr>
                            </thead><tbody>

                            '.$ConditionalmailContent.'

                            </tbody></table>';

                $message .= "</td></tr>";
                $message .= "</table>";

                $message .= "</body></html>";
                try {
                    $mail->IsSMTP();
                    $mail->isHTML(true);
                    $mail->SMTPDebug  = 0;
                    $mail->SMTPAuth   = true;
                    $mail->SMTPSecure = "ssl";
                    $mail->Host       = "smtp.gmail.com";
                    $mail->Port       = 465;
                    // $mail->AddAddress('maha@rigpa.in');
                    $mail->AddAddress($toMail);
                    $mail->Username   ="vinoth@rigpa.in";
                    $mail->Password   ="vinoth@1506";
                    $mail->SetFrom('vinoth@rigpa.in', 'Heera Cars');
                    $mail->Subject    = $ConditionalMailSubject;
                    $mail->Body 	  = $message;
                    $mail->AltBody    = $message;
                    if ($mail->Send()) {
                        $msg = "Mail was successfully sent";
                        $status = "success";
                    }
                } catch (phpmailerException $ex) {
                    $msg = $ex->errorMessage();
                    $status = "error";
                }
                return array("status"=>$status,"msg"=>$msg);
            } else {
                return array("status"=>'error',"msg"=>'invalid mobile number');
            }
        }
    }
?>