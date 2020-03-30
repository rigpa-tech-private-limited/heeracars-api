<?php
    require_once('database.php');
    Class RESTAPIModel{
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
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE mobile = '$mobile' AND active!='2'");
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
                $query = mysqli_query($conn, "SELECT * FROM users WHERE mobile='".$mobile."' AND otp='" . $otp . "' AND is_expired!=1 AND active!='2'");
                $user = mysqli_fetch_assoc($query);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $user;
        }

        function updateToken($otp,$hashed_password){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "UPDATE users SET is_expired = 1, token='".$hashed_password."' WHERE otp = '" . $otp . "'");
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
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE token = '$token' AND active!='2'");
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
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT id,role FROM users WHERE token = '$token' AND active!='2'");
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
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE email = '$email' AND active!='2'");
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
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE mobile = '$mobile' AND active!='2'");
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

        function addAgent($name, $mobile, $email, $company, $location){
            
            $insertFlag  = false;
            try {
                $Dbobj = new DbConnection(); 
                $sql = "INSERT INTO users ( name, mobile, email, company, location, role, created_on ) VALUES ('$name', '$mobile', '$email', '$company', '$location', 'agent', NOW())";
                $query = mysqli_query($Dbobj->getdbconnect(), $sql);
                $insertFlag  = $query;
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $insertFlag;
        }

        function updateAgent($name, $company="", $location="",$id){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE users SET name = '".$name."', company =  '".$company."', location =  '".$location."' WHERE id = '" . $id . "'";
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
                $count  = mysqli_affected_rows($conn);
                
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
                $query = mysqli_query($conn, "SELECT id,name,mobile,email,company,location,active FROM users WHERE role='agent' AND active!='2'");
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

        function resetAgentLogin($id){
            $user = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $otp = rand(100000,999999);
                $hashed_password = password_hash($otp, PASSWORD_BCRYPT, array('cost'=>5));
                $sql = "UPDATE users SET password = 'heera@123', token = '".$hashed_password."', otp = '".$otp."', is_expired = 1 WHERE id = '" . $id . "'";
                $updateQuery = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                if($count > 0){
                    $query = mysqli_query($conn, "SELECT email,mobile,password,token,otp FROM users WHERE id='".$id."' AND role='agent' AND active!='2'");
                    $user = mysqli_fetch_assoc($query);
                }
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
                $query = mysqli_query($conn, "SELECT * FROM car_make WHERE status='Active' ORDER BY display_order DESC");
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
                $query = mysqli_query($conn, "SELECT * FROM car_model WHERE make_id='".$make_id."' AND status='Active' ORDER BY display_order DESC");
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

        function getModelYears($model_id){
            $modelYears = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT * FROM car_variant_year WHERE model_id='".$model_id."' AND status='Active' ORDER BY display_order DESC");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $modelYears[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $modelYears;
        }

        function getModelYearVariants($model_id,$year_id){
            $modelYearVariants = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT * FROM car_fuel_variant WHERE model_id='".$model_id."' AND year_id='".$year_id."' AND status='Active' ORDER BY display_order DESC");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $modelYearVariants[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $modelYearVariants;
        }

        function addQuotations($user_id, $make_id, $make_display, $model_id, $model_display, $year_id, $year, $variant_id, $variant_display, $car_color,$fuel_type,$car_kms,$car_owner,$is_replacement,$structural_damage,$structural_damage_desc,$insurance_date,$refurbishment_cost,$requested_price){
            
            $insertFlag  = false;
            try {
                $Dbobj = new DbConnection(); 
                $sql = "INSERT INTO quotations ( user_id, make_id, make_display, model_id, model_display, year_id, year, variant_id, variant_display, car_color,fuel_type,car_kms,car_owner,is_replacement,structural_damage,structural_damage_desc,insurance_date,refurbishment_cost,requested_price,created_on ) VALUES ('$user_id', '$make_id', '$make_display', '$model_id', '$model_display', '$year_id', '$year', '$variant_id', '$variant_display','$car_color','$fuel_type','$car_kms','$car_owner','$is_replacement','$structural_damage','$structural_damage_desc','$insurance_date','$refurbishment_cost','$requested_price',NOW())";
                $query = mysqli_query($Dbobj->getdbconnect(), $sql);
                $insertFlag  = $query;
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $insertFlag;
        }


        function approveQuotation($id,$approved_price,$approved_by=0){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE quotations SET approved_price='".$approved_price."', approved_by='".$approved_by."', status = '1' WHERE id = '" . $id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function rejectQuotation($id,$dropped_by=0){
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE quotations SET dropped_by='".$dropped_by."', status = '2' WHERE id = '" . $id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function getAllQuotations($user_id=''){
            $quotations = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $condition = '';
                if($user_id != ''){
                    $condition = "WHERE q.user_id='".$user_id."'";
                }
                $sql = "SELECT q.id, q.user_id, u.name, q.make_id, q.make_display, q.model_id, q.model_display, q.year_id, q.year, q.variant_id, q.variant_display, q.car_color, q.fuel_type, q.car_kms, q.car_owner, q.is_replacement, q.structural_damage, q.structural_damage_desc, q.insurance_date, q.refurbishment_cost, q.requested_price, q.approved_price, CASE WHEN q.status = '0' THEN 'Pending' WHEN q.status = '1' THEN 'Approved' ELSE 'Rejected' END AS status FROM quotations q INNER JOIN users u ON q.user_id = u.id ".$condition;
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
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
                $query = mysqli_query($conn, "SELECT image_path FROM quotation_images WHERE quotation_id = ".$quotation_id);
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_array($query)) {
                        $images[] = UPLOAD_BASE_PATH.$row['image_path'];
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
                $query = mysqli_query($conn, "SELECT q.id, q.user_id, u.name, make_id, make_display, model_id, model_display, year_id, year, variant_id, variant_display, car_color, fuel_type, car_kms, car_owner, is_replacement,structural_damage,structural_damage_desc, insurance_date, refurbishment_cost, requested_price, approved_price, CASE WHEN status = '0' THEN 'Pending' WHEN status = '1' THEN 'Approved' ELSE 'Rejected' END AS status FROM quotations q INNER JOIN users u ON q.user_id = u.id WHERE q.id = ".$id);
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

        function addQuotationComments($user_id, $quotation_id, $comments){
            
            $insertFlag  = false;
            try {
                $Dbobj = new DbConnection(); 
                $sql = "INSERT INTO quotation_comments ( user_id, quotation_id, comments, created_on ) VALUES ('$user_id', '$quotation_id', '$comments', NOW())";
                $query = mysqli_query($Dbobj->getdbconnect(), $sql);
                $insertFlag  = $query;
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
                $query = mysqli_query($conn, "SELECT qc.id, qc.user_id, u.role, u.name as user_name, comments, created_on as datetime FROM quotation_comments qc INNER JOIN users u ON qc.user_id = u.id WHERE qc.quotation_id = ".$id);
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $comments[] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $comments;
        }

    }
?>