<?php
    require_once('database.php');
    Class RESTAPIModel{
        function get_all_user_list()
        {
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users");
                $users = mysqli_fetch_assoc($query);
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
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE mobile = '$mobile'");
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
                $query = mysqli_query($conn, "SELECT * FROM users WHERE mobile='".$mobile."' AND otp='" . $otp . "' AND is_expired!=1 AND NOW() <= DATE_ADD(updated_on, INTERVAL 24 HOUR)");
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
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE token = '$token'");
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
                $sql = "INSERT INTO users ( name, mobile, email, company, location, role ) VALUES ('$name', '$mobile', '$email', '$company', '$location','agent')";
                $query = mysqli_query($Dbobj->getdbconnect(), $sql);
                $insertFlag  = $query;
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $insertFlag;
        }

        function getAllAgentsList(){
            $agents = [];
            try {
                $Dbobj = new DbConnection();
                $conn = $Dbobj->getdbconnect();
                $query = mysqli_query($conn, "SELECT id,name,mobile,email,company,location,active FROM users WHERE role='agent'");
                $count  = mysqli_num_rows($query);
                if ($count > 0) {
                    while($row = mysqli_fetch_assoc($query)) {
                        $agents[$row["id"]] = $row;
                    }
                }
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $agents;
        }
    }
?>