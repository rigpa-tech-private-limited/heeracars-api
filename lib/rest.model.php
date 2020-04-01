<?php
    require_once('database.php');
    require_once('Firebase.php');
    require_once('Push.php'); 
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
            
            $$last_id  = '';
            try {
                $Dbobj = new DbConnection(); 
                $conn = $Dbobj->getdbconnect();
                $sql = "INSERT INTO quotations ( user_id, make_id, make_display, model_id, model_display, year_id, year, variant_id, variant_display, car_color,fuel_type,car_kms,car_owner,is_replacement,structural_damage,structural_damage_desc,insurance_date,refurbishment_cost,requested_price,created_on ) VALUES ('$user_id', '$make_id', '$make_display', '$model_id', '$model_display', '$year_id', '$year', '$variant_id', '$variant_display','$car_color','$fuel_type','$car_kms','$car_owner','$is_replacement','$structural_damage','$structural_damage_desc','$insurance_date','$refurbishment_cost','$requested_price',NOW())";
                $query = mysqli_query($conn, $sql);
                $last_id = mysqli_insert_id($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $last_id;
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
                }
                $sql = "SELECT q.id, q.user_id, u.name, q.make_id, q.make_display, q.model_id, q.model_display, q.year_id, q.year, q.variant_id, q.variant_display, q.car_color, q.fuel_type, q.car_kms, q.car_owner, q.is_replacement, q.structural_damage, q.structural_damage_desc, q.insurance_date, q.refurbishment_cost, q.requested_price, q.approved_price, CASE WHEN q.status = '0' THEN 'Pending' WHEN q.status = '1' THEN 'Approved' ELSE 'Rejected' END AS status FROM quotations q INNER JOIN users u ON q.user_id = u.id WHERE q.status IN (0,1,2)".$condition;
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
                $query = mysqli_query($conn, "SELECT id,image_path,image_index FROM quotation_images WHERE quotation_id = ".$quotation_id);
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_array($query)) {
                        $tempArr = [];
                        $tempArr['image_id'] = $row['id'];
                        $tempArr['image_path'] = UPLOAD_BASE_PATH.$row['image_path'];
                        $tempArr['image_index'] = $row['image_index'];
                        $images[] = $row;
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

        function addQuotationImages($quotation_id, $image_path){
            
            $last_id  = '';
            try {
                $Dbobj = new DbConnection(); 
                $conn = $Dbobj->getdbconnect();
                $sql = "INSERT INTO quotation_images ( quotation_id, image_path ) VALUES ('$quotation_id', '$image_path')";
                $query = mysqli_query($conn, $sql);
                $last_id = mysqli_insert_id($conn);
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $last_id;
        }

        function updateQuotationImages($image_id, $image_path){
            
            $count  = 0;
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $sql = "UPDATE quotation_images SET image_path = '".$image_path."' WHERE id = '" . $image_id . "'";
                $query = mysqli_query($conn, $sql);
                $count  = mysqli_affected_rows($conn);
                
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function sendSinglePush($title, $message, $imagePath=''){
            //creating a new push
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
            $devicetoken = array('eiW2dqLz8As:APA91bHt3s4R1cn2ji2DV8WxLdnu7QKHP9hNDxUa7DRRA5NbI6q8vn4dfxiJTHE9uCcB2sYOkSHwJuEZ6kaSgv3SbYMMgx1mYqJWU9khQ1QJnjQP9iuDb63GW2UBgEtYQ3yHW8xcK7BO',"eARc3nz_2yo:APA91bFObTeQxwDKoj-C4n0-LtJGdkYrk6NVVzJWR8s-OsOeJJ-jErOmMwbwnwykp3hucrqNPpmpqFmLns8RQydI-5Oad_8b6cNPE-QitVIRZTi3CpQRrh7YzEk00gbzn_Q_bKl83IP6");

            //creating firebase class object 
            $firebase = new Firebase(); 

            //sending push notification and displaying result 
            echo $firebase->send($devicetoken, $mPushNotification);
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
    }
?>