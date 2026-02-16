<?php
/**
* @package SPLIB
* @version $Id: AccountMaintenance.php,v 1.6 2003/11/13 05:37:28 kevin Exp $
*/
/**
* Constants which define table and column names
*/
# Modify this constant to reflect session variable name
// Name to use for login variable used in Auth class
@define ( 'USER_LOGIN_VAR','login');

# Modify these constants to match your user login table
// Name of users table
@define ( 'USER_TABLE','users');
// Name of user_id column in table
@define ( 'USER_TABLE_ID','userID');
// Name of login column in table
@define ( 'USER_TABLE_LOGIN','login');
// Name of password column in table
@define ( 'USER_TABLE_PASSW','password');
// Name of email column in table
@define ( 'USER_TABLE_EMAIL','email');
// Name of firstname column in table
@define ( 'USER_TABLE_FIRST','firstName');
// Name of lastname column in table
@define ( 'USER_TABLE_LAST','lastName');
/**
* AccountMaintenance Class<br />
* Provides functionality for users to manage their own accounts
* @access public
* @package SPLIB
*/
class AccountMaintenance {
    /**
    * Database connection
    * @access private
    * @var object
    */
    var $db;

    /**
    * A list of words to use in generating passwords
    * @access private
    * @var array
    */
    var $words;

    /**
    * AccountMaintenance constructor
    * @param object instance of database connection
    * @access public
    */
    function AccountMaintenance (&$db) {
        $this->db=& $db;
    }

    /**
    * Given an email address, returns the user details
    * that account. Useful is password is not encrpyted
    * @param string email address
    * @return array user details
    * @access public
    */
    function fetchLogin($email) {
        // Use generic addslashes() to escape dangerous characters instead of deprecated mysql_* functions
        $email = addslashes($email);
        $sql="SELECT
                  ".USER_TABLE_LOGIN.", ".USER_TABLE_PASSW.",
                  ".USER_TABLE_FIRST.", ".USER_TABLE_LAST."
              FROM
                  ".USER_TABLE."
              WHERE
                  ".USER_TABLE_EMAIL."='".$email."'";
        $result=$this->db->query($sql);
        if ( $result->size() == 1 )
            return $result->fetch();
        else
            return false;
    }

    /**
    * Given a username / email combination, resets the password
    * for that user and returns the new password.
    * @param string login name
    * @param string email address
    * @return array of user details or FALSE if failed
    * @access public
    */
    function resetPassword($login,$email) {
        // Escape inputs without using deprecated mysql_* extension
        $login = addslashes($login);
        $email = addslashes($email);
        $sql="SELECT ".USER_TABLE_ID.",
                  ".USER_TABLE_LOGIN.", ".USER_TABLE_PASSW.",
                  ".USER_TABLE_FIRST.", ".USER_TABLE_LAST."
              FROM
                  ".USER_TABLE."
              WHERE
                  ".USER_TABLE_LOGIN."='".$login."'
              AND
                  ".USER_TABLE_EMAIL."='".$email."'";
        $result=$this->db->query($sql);
        if ( $result->size() == 1 ) {
            $row=$result->fetch();
            if ( $password = $this->generatePassword() ) {
                $sql="UPDATE
                          ".USER_TABLE."
                      SET
                          ".USER_TABLE_PASSW."='".md5($password)."'
                      WHERE
                          ".USER_TABLE_ID."='".$row[USER_TABLE_ID]."'";
			  
                $result=$this->db->query($sql);
                if (!$result->isError()) {
                    $row[USER_TABLE_PASSW]=$password;
                    return $row;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
    * Add a list of words to generate passwords with
    * @param array
    * @return void
    * @access public
    */
    function addWords ($words) {
        $this->words=$words;
    }

    /**
    * Generates a random but memorable password
    * @return string the password
    * @access private
    */
    function generatePassword () {
        srand((double)microtime()*1000000);
        $seperators=range(0,9);
        $seperators[]='_';
        $count=count($this->words);
        if ( $count == 0 )
            return false;
        $password=array();
        for ( $i=0;$i<4;$i++ ) {
            if ( $i % 2 == 0 ) {
                shuffle ($this->words);
                $password[$i]=trim($this->words[0]);
            } else {
                shuffle ( $seperators );
                $password[$i]=$seperators[0];
            }
        }
        shuffle($password);
        return implode ('',$password);
    }

    /**
    * Changes a password both in the database
    * and in the current session variable.
    * Assumes the new password has been
    * validated correctly elsewhere.
    * @param string old password
    * @param string new password
    * @return boolean TRUE on success
    * @access public
    */
    function changePassword(& $auth,$oldPassword,$newPassword) {
        // Escape passwords without relying on deprecated mysql_* extension
        $oldPassword = addslashes($oldPassword);
        $newPassword = addslashes($newPassword);

        // Instantiate the Session class
        $session=new Session();

        // Check the the login and old password match
        $sql="SELECT
                  *
              FROM
                  ".USER_TABLE."
              WHERE
                  ".USER_TABLE_LOGIN."='".$session->get(USER_LOGIN_VAR)."'
              AND
                  ".USER_TABLE_PASSW."='".md5($oldPassword)."'";
        $result=$this->db->query($sql);
        if ( $result->size() != 1 )
            return false;

        // Update the password
        $sql="UPDATE
                  ".USER_TABLE."
              SET
                  ".USER_TABLE_PASSW."='".md5($newPassword)."'
              WHERE
                  ".USER_TABLE_LOGIN."='".$session->get(USER_LOGIN_VAR)."'";
        $result=$this->db->query($sql);
        if ( !$result->isError() ) {
            // Set the session variable for the password
            $auth->storeAuth($session->get(USER_LOGIN_VAR),$newPassword);
            return true;
        } else {
            return false;
        }
    }
}
?>