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

        function updateOTP(){
            $count  = 0;
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "UPDATE users SET otp = ".$otp.", is_expired = 0, updated_on='NOW()' WHERE mobile = '" . $mobile . "'");
                $count  = mysqli_affected_rows($Dbobj->getdbconnect());
                
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }

        function verifyOTP($mobile,$otp){
            $user = null;
            try {
                $Dbobj = new DbConnection(); 
                $query = mysqli_query($Dbobj->getdbconnect(), "SELECT * FROM users WHERE mobile=".$mobile." AND otp='" . $otp . "' AND is_expired!=1 AND NOW() <= DATE_ADD(updated_on, INTERVAL 24 HOUR)");
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
                $query = mysqli_query($Dbobj->getdbconnect(), "UPDATE users SET is_expired = 1, token='".$hashed_password."' WHERE otp = '" . $otp . "'");
                $count  = mysqli_affected_rows($Dbobj->getdbconnect());
            } catch (Exception $e) {
                print "Error!: " . $e->getMessage() . "<br/>";
                die();
            }
            return $count;
        }
    }
?>