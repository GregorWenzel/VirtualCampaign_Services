<?php

require 'base.class.php';

/**
 * ----------------------------------------------------------------------------
 * File: 		account.class.php
 * 
 * @author  	Christiane Kuhn <kuhn@webdevelopment-koeln.de>
 * @link 		http://webdevelopment-koeln.de
 * 
 * @category	Virtual Campaign 
 *
 * 
 * This Script was developed by Christiane Kuhn - ChK Web-Development.
 * You are authorised to use this Script on your internet sides.
 * The right to use applies only to the direct use of the software by HERZBERGMEDIA/ibt-studios for Virtual Campaign.
 * You are not authorised to resale, to lease or to publish the software outside of HERZBERGMEDIA/ibt-studios for Virtual Campaign.
 *
 * The passing of this script is not allowed.
 *
 * Please do not remove this header.
 *
 * ----------------------------------------------------------------------------
 * Rev: 	2012-01 	ChK	Created
 * 
 * ----------------------------------------------------------------------------
 *
 */

/**
 * class account
 *
 */
class account extends baseClass
{
	public $_dbconn;
	public $_account_id;
	public $_account_group_id;
	public $_error;
	public $_errorArr;
	
	/**
	 * set required params for actions
	 * defined foreach action
	 */
	protected $_required =  array(
				'login' => array(array('name' => 'username'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)		
								,array('name' => 'password'
										,'type' => 'string'			
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)	
								)
				,'logout' => array(array('name' => 'accountID'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								)
				,'createAccount' => array(array('name' => 'accountId'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'accountGroupId'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'language_iso_code'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '5' 
										)	
								,array('name' => 'username'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)		
								,array('name' => 'password'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'firstName'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)	
								,array('name' => 'lastName'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'email'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								/*
								,array('name' => 'phone'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '45' 
										)
								,array('name' => 'company'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'description'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								*/
								,array('name' => 'is_active'
										,'type' => 'int'
										,'musthave' => 'required'
										,'maxstringlen' => '' 
										)
								/*
								,array('name' => 'mail_txt_delete_film'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '1000' 
										)		
								,array('name' => 'mail_txt_film_ready'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '1000' 
										)
								,array('name' => 'days_keep_rendered_film'
										,'type' => 'int' 
										,'musthave' => 'optional' 
										,'maxstringlen' => ''      
										)	
								*/
								,array('name' => 'budget'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => ''
										)
								,array('name' => 'quota'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => ''
										)
								)
				,'updateAccount' => array(array('name' => 'accountId'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'accountGroupId'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'language_iso_code'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '5' 
										)	
								,array('name' => 'username'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)		
								,array('name' => 'password'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'firstName'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)	
								,array('name' => 'lastName'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'email'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)
								/*
								,array('name' => 'phone'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '45' 
										)
								,array('name' => 'company'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'description'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								*/
								,array('name' => 'is_active'
										,'type' => 'int'
										,'musthave' => 'required'
										,'maxstringlen' => '' 
										)
                                ,array('name' => 'multilogin'
                                    ,'type' => 'int'
                                    ,'musthave' => 'required'
                                    ,'maxstringlen' => ''
                                    )
								/*
								,array('name' => 'mail_txt_delete_film'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '1000' 
										)		
								,array('name' => 'mail_txt_film_ready'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '1000' 
										)
								,array('name' => 'days_keep_rendered_film'
										,'type' => 'int' 
										,'musthave' => 'optional' 
										,'maxstringlen' => ''      
										)	
								*/
								,array('name' => 'budget'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => ''
										)
								,array('name' => 'quota'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => ''
										)
								)
				,'deleteAccount' => array(array('name' => 'userID'
										,'type' => 'int' 
										,'musthave' => 'required'
										,'maxstringlen' => ''      
										)
								)					
				,'getProductAssociations' => array(array('name' => 'accountGroupID'
										,'type' => 'int' 
										,'musthave' => 'required'
										,'maxstringlen' => ''      
										)
								)		
				,'getGroupAssociations' => array(array('name' => 'productID'
										,'type' => 'int' 
										,'musthave' => 'required'
										,'maxstringlen' => ''      
										)
								)								
				,'createAccountGroup' => array(array('name' => 'groupName'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'groupDescription'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'groupCustom'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'groupCustomObligate'
										,'type' => 'int'
										,'musthave' => 'required'
										,'maxstringlen' => '' 
										)
								,array('name' => 'associationTasks'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '4096' 
										)									
								,array('name' => 'groupAbdicative'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '' 
										)										
								,array('name' => 'groupIndicative'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '' 
										)
                                ,array('name' => 'onlinethreesixty'
                                    ,'type' => 'int'
                                    ,'musthave' => 'required'
                                    ,'maxstringlen' => ''
                                    )
                                ,array('name' => 'billing_type'
                                    ,'type' => 'string'
                                    ,'musthave' => 'optional'
                                    ,'maxstringlen' => '64'
                                    )
                                ,array('name' => 'credits'
                                    ,'type' => 'decimal'
                                    ,'musthave' => 'optional'
                                    ,'maxstringlen' => ''
                                    )
								)
				,'updateAccountGroup' => array(array('name' => 'accountGroupID'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'groupName'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '45'      
										)
								,array('name' => 'groupDescription'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'groupCustom'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)
								,array('name' => 'groupCustomObligate'
										,'type' => 'int'
										,'musthave' => 'required'
										,'maxstringlen' => '' 
										)										
								,array('name' => 'groupIndicative'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '' 
										)
								,array('name' => 'groupAbdicative'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '' 
										)
								,array('name' => 'associationTasks'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '1024' 
										)
                                ,array('name' => 'onlinethreesixty'
                                    ,'type' => 'int'
                                    ,'musthave' => 'required'
                                    ,'maxstringlen' => ''
                                    )
                                ,array('name' => 'billing_type'
                                    ,'type' => 'string'
                                    ,'musthave' => 'optional'
                                    ,'maxstringlen' => '64'
                                    )
                                ,array('name' => 'credits'
                                    ,'type' => 'decimal'
                                    ,'musthave' => 'optional'
                                    ,'maxstringlen' => ''
                                    )
								)
				,'deleteAccountGroup' => array(array('name' => 'groupID'
										,'type' => 'int' 
										,'musthave' => 'required'
										,'maxstringlen' => ''      
										)
								)
                ,'setMaintain' => array(array('name' => 'status'
										,'type' => 'int'
										,'musthave' => 'required'
										,'maxstringlen' => ''
										)
								)
				,'migrateStatisticItem' => array(array('name' => 'NewOwner'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'ProductID'
										,'type' => 'string' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'Type'
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => '255'      
										)
								,array('name' => 'Date'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)			
								,array('name' => 'Motifs'
										,'type' => 'string'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)											
								)	
				,'getStatistics' => array(array('name' => 'AccountID'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'StartDate'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)			
								,array('name' => 'EndDate'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)											
								)	
				,'exportStatistics' => array(array('name' => 'AccountID'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'StartDate'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)			
								,array('name' => 'EndDate'
										,'type' => 'int'
										,'musthave' => 'optional'
										,'maxstringlen' => '255' 
										)											
								)						
				,'adjustNamedObject' => array(array('name' => 'AccountID'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'OwnerID'
										,'type' => 'int'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)			
								,array('name' => 'Name'
										,'type' => 'string'
										,'musthave' => 'required'
										,'maxstringlen' => '3000' 
										)
								,array('name' => 'ID'
										,'type' => 'int'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)	
								,array('name' => 'DateSent'
										,'type' => 'int'
										,'musthave' => 'required'
										,'maxstringlen' => '255' 
										)											
								)	
            ,'addCreditsToAccountGroup' => array(array('name' => 'AccountGroupID'	
										,'type' => 'int' 
										,'musthave' => 'required' 
										,'maxstringlen' => ''      
										)
								,array('name' => 'CreditsAmount'
										,'type' => 'decimal'
										,'musthave' => 'required'
										,'maxstringlen' => '' 
										)										
                                )
				);
				
	/**
	 * Constructor
	 *
	 * @param obj $dbconn
	 * @param int $account_id = null
	 * 
	 */
	public function __construct($dbconn, $account_id = null, $account_group_id = null)
	{
        parent::__construct($dbconn);
        $accountId = $account_id;
        $this->_account_group_id 	= $account_group_id;
	}
	
	/**
	 * login
	 *
	 * @param array $valueArr
	 * 
	 * @return array or null
	 */
    public function login($valueArr)
    {
        if(!format::check_params($valueArr, $this->_required['login'])) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return false;
        }

        $valueArr = format::$_returnArr;
        $userName = $this->escapeData($valueArr['username']);
        $password = $this->escapeData($valueArr['password']);

        //update online status for all users
        $ts = strtotime("-1 minute");
        $sql = "UPDATE account
            SET
                is_online = 0
            WHERE
                last_login < FROM_UNIXTIME(".$ts.")";
        $this->dbQuery($sql, ['SQL-ERROR'=>'account->login_updateaccout']);
        if ($this->_error) {
            return false;
        }

        // get current user
        $sql = "SELECT
              a.*,
              ag.onlinethreesixty,
              ag.indicative,
              ag.abdicative
            FROM account AS a
            INNER JOIN account_group AS ag
                ON a.account_group_id = ag.account_group_id
            WHERE
                a.login_username 	= '" . $userName . "'
                AND a.delete_ts IS NULL
                AND a.is_active = 1
                AND a.login_pw 	= PASSWORD('" . $password . "')
            LIMIT 1";
        $res =  $this->dbSelect($sql, ['SQL-ERROR'=>'account->login get data'], true);
        if ($this->_error) {
            return false;
        }
        if (empty($res)) {
            return ['result' => 0];
        }
        $ds = $res[0];
        // check is users allow to login
        if (!$ds['multilogin'] && $ds['is_online'] ) {
            return ['result' => 3];
        }

        // update current user online status
        $sql = "UPDATE account
            SET
                is_online	= 1,
                last_login = now()
            WHERE
                account_id = " . (int)$ds['account_id']. ";";
        $this->dbQuery($sql, ['SQL-ERROR'=>'account->login']);
        if ($this->_error) {
            return false;
        }

        return [
            'result' => 2,
            'data' => $ds,
        ];
    }


	/**
	 * logout
	 *
	 * @param array $valueArr
	 * 
	 * @return bool
	 */
	public function logout($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['logout']);

		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
/* old script logout.php
 * $query = "UPDATE users SET online=0, heartbeat = 0 WHERE ID=".$_POST['ID'];
 */
			$sql = "update account"
					." set" 
					.	" is_online		= 0" 
					." where" 
					.	" account_id		= " . (int)$valueArr['accountID'] 
					.";";
				    
			echo "<SQL>".$sql."</SQL>";
			if (!$this->_dbconn->query($sql)) {
				$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->logout'),$this->_dbconn->error);
				return false;
			}
		}
		return (int)$valueArr['accountID'];
	}
	
	/**
	 * createAccount
	 *
	 * @param array $valueArr
	 * 
	 * @return bool
	 */
	public function createAccount($valueArr) {
        if(false === format::check_params($valueArr, $this->_required['createAccount'])) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return null;
        }

        $valueArr = format::$_returnArr;
        $exists = $this->checkUniqueAccountName($valueArr['username']);
        if(false !== $exists) {
            return false;
        }

        $sql = "INSERT INTO account
            SET
                account_group_id = " . (int)$this->escapeData($valueArr['accountGroupId']) . ",
                language_iso_code = '" . $this->escapeData($valueArr['language_iso_code']) . "'" .	",
                login_username 	= '" . $this->escapeData($valueArr['username']) . "'" . ",
                login_pw = PASSWORD('" . $this->escapeData($valueArr['password']) . "')" . 	",
                name = '" . $this->escapeData($valueArr['firstName']) . "'" .	",
                last_name = '" . $this->escapeData($valueArr['lastName']) . "'" .	",
                email = '" . $this->escapeData($valueArr['email']) . "'" .	",
                creation_time 	= now(),
                is_online = 0,
                is_active = " . (int)$valueArr['is_active'] .	",
                quota = ". (int)$valueArr['quota'] . ",
                budget = ". (int)$valueArr['budget'] . ",
                multilogin = ". (int)$valueArr['multilogin'] . ",
                delete_ts = null
        ";

        $this->dbQuery($sql, ['SQL-ERROR' => 'account->createAccount']);
        if ($this->_error) {
            return false;
        }

        $this->_account_id = $this->_dbconn->insert_id;
        $this->createAccountDirectories($this->_account_id);

        return true;
	}
	
	/**
	 * updateAccount
	 *
	 * @param array $valueArr
	 * 
	 * @return bool
	 */
	public function updateAccount($valueArr)
    {
        if(false === format::check_params($valueArr, $this->_required['updateAccount'])) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return null;
        }

        $valueArr = format::$_returnArr;
        $exists = $this->checkUniqueAccountName($valueArr['username'], $valueArr['accountId']);
        if(false !== $exists) {
            return false;
        }

        $sql = "UPDATE account"
            ." SET"
            .	" account_group_id	= " . (int)$valueArr['accountGroupId']
            .	",language_iso_code = '" . $this->escapeData($valueArr['language_iso_code']) . "'"
            .	",login_username 	= '" . $this->escapeData($valueArr['username']) . "'"
            .	",name 				= '" . $this->escapeData($valueArr['firstName']) . "'"
            .	",last_name 		= '" . $this->escapeData($valueArr['lastName']) . "'"
            .	",email 			= '" . $this->escapeData($valueArr['email']) . "'"
            .	",is_active 		= " . (int)$valueArr['is_active']
            .	",quota				= ". (int)$valueArr['quota']
            .	",budget			= ". (int)$valueArr['budget']
            .	",multilogin	    = ". (int)$valueArr['multilogin'];

        if (isset($valueArr['password']))
            $sql .=	",login_pw 			= PASSWORD('" . $this->escapeData($valueArr['password']) . "')";

        $sql .= " WHERE"
            .	" account_id		= " . (int)$valueArr['accountId'] . ";";

        $this->dbQuery($sql, ['SQL-ERROR' => 'account->updateAccount']);
        if ($this->_error) {
            return false;
        }

        $this->_account_id = (int)$valueArr['accountId'];
        $this->createAccountDirectories((int)$valueArr['accountId']);

        return true;
	}

    /**
     * @param int $accountId
     */
	public function createAccountDirectories($accountId)
	{
		$user_dir = UPLOAD_DIR . $accountId;
		echo "<DIR>".$user_dir."</DIR>";
		if(!is_dir($user_dir)) {
			mkdir($user_dir, 0755);
			chmod ($user_dir, 0755); //for sure
		}
		
		$user_dir = UPLOAD_DIR . $accountId."/motifs/";
		echo "<DIR>".$user_dir."</DIR>";
		if(!is_dir($user_dir)) {
			mkdir($user_dir, 0755);
			chmod ($user_dir, 0755); //for sure
		}
		
		$user_dir = UPLOAD_DIR . $accountId."/productions/";
		echo "<DIR>".$user_dir."</DIR>";
		if(!is_dir($user_dir)) {
			mkdir($user_dir, 0755);
			chmod ($user_dir, 0755); //for sure
		}
		
		$user_dir = UPLOAD_DIR . $accountId."/temp/";
		echo "<DIR>".$user_dir."</DIR>";
		if(!is_dir($user_dir)) {
			mkdir($user_dir, 0755);
			chmod ($user_dir, 0755); //for sure
		}
	}
	
	/**
	 * deleteAccount
	 *
	 * @param array $valueArr
	 * 
	 * @return bool
	 */
	public function deleteAccount($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['deleteAccount']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			//delete account
/* old script: delete_user.php
 * $query = "DELETE FROM users WHERE ID=".$_POST['userID'];
 */
//TODO: klären, ob hier noch geprüft werden muss, ob Abhängigkeiten bestehen						
			$sql = "delete from account"
					." where" 
					.	" account_id	= " . (int)$valueArr['userID'] 
					.";";
				    
			if (!$this->_dbconn->query($sql)) {
				$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->deleteAccount'),$this->_dbconn->error);
				return false;
			}
		}
		return true;
	}
	
	/**
	 * checkUniqueAccountName
	 *
	 * @param string $login_name
	 * @param int $account_id
	 * @return bool or null
	 */
	protected function checkUniqueAccountName($login_name, $account_id = null)
	{
		$exists = false;
		$sql = "select account_id from account
				where
					login_username 	= '" . $this->_dbconn->real_escape_string($login_name) . "'
				;";
		if ($res =  $this->_dbconn->query($sql)) {
			if($res->num_rows > 0) {
				//check for own update
				$ds = $res->fetch_array();
				if($account_id != $ds['account_id']) {
					$this->_error = error::buildErrorString(array(''=>locale::ERR_USER_UNIQUE));
					$this->_errorArr['username'] = locale::ERR_USER_UNIQUE;
					$exists = true;
				}
			}
		} else {
			$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->checkUniqueAccountName'),$this->_dbconn->error);
			return null;
		}
		return $exists;
	}
	
	/**
	 * checkUniqueEmail
	 *
	 * @param string $email
	 * @param int $account_id
	 * @return bool or null
	 */
	protected function checkUniqueEmail($email, $account_id=null)
	{
		$exists=false;
		$sql = "select account_id from account
				where
					email 	= '" . $this->_dbconn->real_escape_string($email) . "'
				;";
		if ($res =  $this->_dbconn->query($sql)) {
			if($res->num_rows > 0) {
				//check for own update
				$ds = $res->fetch_array();
				if($account_id != $ds['account_id']) {
					$this->_error .= '<br />' . error::buildErrorString(array(''=>locale::ERR_EMAIL_UNIQUE));
					$this->_errorArr['email'] = locale::ERR_EMAIL_UNIQUE;
					return null;
				}
			}
		} else {
			$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->checkUniqueEmail'),$this->_dbconn->error);
			return null;
		}
		return $exists;
	}
	
	
	/**
	 * createAccountGroup
	 *
	 * @param array $valueArr
	 * 
	 * @return bool
	 */
	public function createAccountGroup($valueArr)
    {
        if(false === format::check_params($valueArr, $this->_required['createAccountGroup'])) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return null;
        }

        $valueArr = format::$_returnArr;
        $exists = $this->checkUniqueAccountGroupName($valueArr['name']);
        if(false !== $exists) {
            return false;
        }

        if (!isset($valueArr['groupCustom']))
            $valueArr['groupCustom'] = "";

        if (!isset($valueArr['credits']))
            $valueArr['credits'] = 0;

        if (!isset($valueArr['billing_type']))
            $valueArr['billing_type'] = "flatrate";

        $sql = "INSERT INTO account_group"
            ." SET"
            .	" name 			= '" . $this->escapeData($valueArr['groupName']) . "'"
            .	",description 	= '" . $this->escapeData($valueArr['groupDescription']) . "'"
            .	",indicative 	= "  . (int)$valueArr['groupIndicative']
            .	",abdicative 	= "  . (int)$valueArr['groupAbdicative']
            .	",account_group_custom = '".$this->escapeData($valueArr['groupCustom']) . "'"
            .	",onlinethreesixty = '". (int)$valueArr['onlinethreesixty'] . "'"
            .	",account_group_custom_obligate = ".(int)$valueArr['groupCustomObligate']
            .   ",billing_type  = " . $this->escapeData($valueArr['billing_type']) . "'" 
            .   ",credits      = "  . $valueArr['credits']
            .	",create_ts		= null;";

        $this->dbQuery($sql, ['SQL-ERROR' => 'account->createAccountGroup']);
        if ($this->_error) {
            return false;
        }

        $this->_account_group_id = $this->_dbconn->insert_id;
        $this->performTasks($valueArr['associationTasks']);

        return true;
	}

    public function addCreditsToAccountGroup($valueArr)
    {
        if(false === format::check_params($valueArr, $this->_required['addCreditsToAccountGroup'])) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return null;
        }

        $sql = "UPDATE accountGroup SET credits = credits + ".$valueArr['CreditsAmount']
            ." WHERE account_group_id = ".(int)$valueArr['AccountGroupId'];

        $this->dbQuery($sql, ['SQL-ERROR' => 'account->addCreditsToAccountGroup']);
        if ($this->_error) {
            return false;
        }

        $this->addTransaction($valueArr);

        return true;
    }

    public function addTransaction($valueArr)
    {
        $sql    =   "INSERT INTTO transaction"
                    ." account_group_id = ".(int)$valueArr['AccountGroupId']
                    .",amount = ".$valueArr['CreditsAmount'];

        if (isset($valueArr["FilmID"]))
            $sql .= ",film_id = ".(int)$valueArr["FilmID"];

        if (isset($valueArr["AccountID"]))
            $sql .= ",account_id = ".(int)$valueArr["AccountID"];

        $this->dbQuery($sql, ['SQL-ERROR' => 'account->addTransaction']);
        if ($this->_error) {
            return false;
        }       

        return true;   
    }
	
	/**
	 * updateAccountGroup
	 *
	 * @param array $valueArr
	 * 
	 * @return bool
	 */
	public function updateAccountGroup($valueArr)
    {
        if(false === format::check_params($valueArr, $this->_required['updateAccountGroup'])) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return null;
        }

        $valueArr = format::$_returnArr;
        $exists = $this->checkUniqueAccountGroupName($valueArr['groupName'], $valueArr['accountGroupID']);
        if(false !== $exists) {
            return false;
        }
                
        if (!isset($valueArr['groupCustom']))
            $valueArr['groupCustom'] = "";

        if (!isset($valueArr['credits']))
            $valueArr['credits'] = 0;

        if (!isset($valueArr['billing_type']))
            $valueArr['billing_type'] = "flatrate";

        $sql = "UPDATE account_group"
            ." SET"
            .	" name 			= '" . $this->escapeData($valueArr['groupName']) . "'"
            .	",description 	= '" . $this->escapeData($valueArr['groupDescription']) . "'"
            .	",indicative 	= " . (int)$valueArr['groupIndicative']
            .	",abdicative 	= " . (int)$valueArr['groupAbdicative']
            .	",account_group_custom = '" . $this->escapeData($valueArr['groupCustom']) . "'"
            .	",onlinethreesixty = ". (int)$valueArr['onlinethreesixty']
            .	",account_group_custom_obligate = ".(int)$valueArr['groupCustomObligate']
            .   ",billing_type  = '" . $this->escapeData($valueArr['billing_type']) . "'" 
            .   ",credits      = "  . $valueArr['credits']
            ." WHERE"
            .	" account_group_id		= " . (int)$valueArr['accountGroupID']
            .";";

        $this->dbQuery($sql, ['SQL-ERROR' => 'account->updateAccountGroup']);
        if ($this->_error) {
            return false;
        }

        $this->_account_group_id = (int)$valueArr['accountGroupID'];
        $this->performTasks($valueArr['associationTasks']);

        return true;
	}

	public function performTasks($tasks) {
		$taskList = explode(".", $tasks);
		
		$taskCount = count($taskList);
				
		$audioUpdated = false;
		
		for ($i=0; $i<$taskCount; $i++)
		{
			$currentTask = $taskList[$i];
			list($association, $action, $parentID, $childID) = explode(",",$currentTask);

			if ((int)$parentID < 0)
			{
				$parentID = $this->_account_group_id;
			}
			
			if ($association == "accountGroup2account")
			{				
				$this->modifyAccountAssociation($action, $parentID, $childID);
			}
			else if ($association == "accountGroup2product")
			{
				$this->modifyProductAssociation($action, $parentID, $childID);
			}
			else if ($association == "accountGroup2audio")
			{
				if ($audioUpdated == false)
				{
					$audioUpdated = true;
					$sql = "delete from xref_audio_group where account_group_id = ".$parentID;
					print("<SQL>".$sql."</SQL>");
					$this->_dbconn->query($sql);
				}
				$this->modifyAudioAssociation($action, $parentID, $childID);
			}
		}
	}
	
	public function modifyAudioAssociation($action, $parentID, $childID)
	{
		if ($action == "add")
		{
			$sql = "insert into xref_audio_group set account_group_id = ".$parentID.", audio_id = ".$childID;
		}
		
		$this->_dbconn->query($sql);
	}

	public function modifyAccountAssociation($action, $parentID, $childID)
	{
		if ($action == "add")
		{
			$sql = "update account set account_group_id = ".$parentID." where account_id = ".$childID;
		}
		else if ($action == "remove")
		{
		}
		
		//echo "<SQL>".$sql."</SQL>";
		
		$this->_dbconn->query($sql);
	}
	
	public function modifyProductAssociation($action, $parentID, $childID)
	{
		if ($action == "add")
		{
			$sql = "insert into xref_account_group_product (account_group_id, product_id) values (".$parentID.", ".$childID.")";
		}
		else if ($action == "remove")
		{
			$sql = "delete from xref_account_group_product where account_group_id = ".$parentID." and product_id = ".$childID;
		}
		echo "<SQL>".$sql."</SQL>";
		$this->_dbconn->query($sql);
	}

	public function migrateStatisticItem($valueArr) {
		$output = "";
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['migrateStatisticItem']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return $this->error;
		} else {
			$valueArr = format::$_returnArr;
			
			if ((int)$valueArr['Type'] == 0)
			{
				$asset_type = "film";
				$asset_id = -1;
				$account = $valueArr['NewOwner'];
				$asset_date = ((int)$valueArr['Date']);
				
				$sql = "INSERT INTO history SET"
					  ."  asset_type = '".$asset_type."'"
					  .", asset_id = -1"
					  .", account_account_id = ".$account
					  .", asset_date = FROM_UNIXTIME(".$asset_date.")"
					  .", asset_timestamp = ".$asset_date
					  .";";
				$this->_dbconn->query($sql);
				$output .= "<SQL>".$sql."</SQL>";
			}
			else if ((int)$valueArr['Type'] == 1)
			{
				$asset_type = "clip";
				$asset_id = $valueArr['ProductID'];
				$account = $valueArr['NewOwner'];
				$asset_date = ((int)$valueArr['Date']);
				
				$sql = "INSERT INTO history SET"
					  ."  asset_type = '".$asset_type."'"
					  .", asset_id = ".$asset_id
					  .", account_account_id = ".$account
					  .", asset_date = FROM_UNIXTIME(".$asset_date.")"
					  .", asset_timestamp = ".$asset_date		  
					  .";";
				$this->_dbconn->query($sql);	
				$output .= "<SQL>".$sql."</SQL>";
				
				$motifIDs = explode(",",$valueArr['Motifs']);
				
				for ($i=0; $i<count($motifIDs); $i++)
				{
					$asset_type = "motif";
					$asset_id = $motifIDs[$i];
					$account = $valueArr['NewOwner'];
					$asset_date = ((int)$valueArr['Date']);
					
					$sql = "INSERT INTO history SET"
					  ."  asset_type = '".$asset_type."'"
					  .", asset_id = ".$asset_id
					  .", account_account_id = ".$account
					  .", asset_date = FROM_UNIXTIME(".$asset_date.")"
					  .", asset_timestamp = ".$asset_date			  
					  .";";
					$this->_dbconn->query($sql);	
					$output .= "<SQL>".$sql."</SQL>";					
				}
			}
		}
		
		return $output;
	}
	
	/**
	 * deleteAccountGroup
	 *
	 * @param array $valueArr
	 * 
	 * @return bool
	 */
	public function deleteAccountGroup($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['deleteAccountGroup']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
//TODO: klären, ob hier noch geprüft werden muss, ob Abhängigkeiten bestehen			
			$sql = "delete from account_group"
				." where" 
				.	" account_group_id	= " . (int)$valueArr['groupID'] 
				.";";
				    
			if (!$this->_dbconn->query($sql)) {
				$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->deleteAccountGroup'),$this->_dbconn->error);
				return false;
			}
		}
		return true;
	}
	
	/**
	 * checkUniqueAccountGroupName
	 *
	 * @param string $groupname
	 * @param int $accountgroup_id
	 * @return bool or null
	 */
	protected function checkUniqueAccountGroupName($groupname, $accountgroup_id = null)
	{
		$exists = false;
		$sql = "select account_group_id from account_group
				where
					name = '" . $this->_dbconn->real_escape_string($groupname) . "'
					AND delete_ts IS NULL
				;";
		if ($res =  $this->_dbconn->query($sql)) {
			if($res->num_rows > 0) {
				//check for own update
				$ds = $res->fetch_array();
				if($accountgroup_id != $ds['account_group_id']) {
					$this->_error = error::buildErrorString(array(''=>locale::ERR_GROUP_UNIQUE));
					$this->_errorArr['name'] = locale::ERR_GROUP_UNIQUE;
					return null;
				}
			}
		} else {
			$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->checkUniqueAccountGroupName'),$this->_dbconn->error);
			return null;
		}
		return $exists;
	}
	
	/**
	 * sendDeleteInfoMail
	 * 
	 * @return bool 
	 */
	public function sendDeleteInfoMail() {
		$success = true;
		if(!class_exists('mail', false)) {
			require 'mail.class.php';
		}
		$mail = new mail();
		//set mail-subject
		$subject = locale::DELETE_REMINDER_MAIL_SUBJECT;
		
		$today_ts 	= strtotime(date('Y-m-d 23:59:59'));
		
		//get all accounts
		$sql = "select account_id, email, days_keep_rendered_film, mail_txt_delete_film  from account";
		if ($res =  $this->_dbconn->query($sql)) {
			while ($ds = mysqli_fetch_array($res)) {
				if((int)$ds['days_keep_rendered_film'] > 0) {
					//set mail-body
					$body = $ds['mail_txt_delete_film'];
					$send_mail = false;
					
					//0 = unlimited => only check for > 0
					$sql_film = "select creation_time, name, description, flv_url from film where account_id = " . (int)$ds['account_id'];
					if ($res_film =  $this->_dbconn->query($sql_film)) {
						while ($ds_film = mysqli_fetch_array($res_film)) {
							//for sure init
							$diffTime = 0;
							$daysCount = 0;
							$creation_ts = strtotime($ds_film['creation_time']);
							$diffTime = $today_ts - $creation_ts;
							$daysCount = floor($diffTime/86400);
							if((int)$daysCount > (int)$ds['days_keep_rendered_film']) {
								$send_mail = true;
								//show flv-list
								if('' != $ds_film['flv_url']) {
									$body .= "\n\r" . $ds_film['flv_url'];
								}
							}
						}//while film
					} else {
						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->sendDeleteInfoMail read films'),$this->_dbconn->error);
						$success = false;
					}
//TODO: dummy for test	
					//echo $body; 
					if($send_mail) {
						echo $body;
//TODO: mail aktivieren						 
						/* 
						if(!$mail->sendmail('',$ds['email'], $subject, $body)) {
							$this->_error .= $mail->_error;
							$success = false;
						}
						*/
					}	
				}//>0
   			}//while account
		} else {
			$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->sendDeleteInfoMail read accounts'),$this->_dbconn->error);
			$success = false;
		}
		return $success; 
	}
	
	/**
	 * deleteOldFilmsByAccount
	 * 
	 * @return bool 
	 */
	public function deleteOldFilmsByAccount() {
		$success = true;
		
		$today_ts 	= strtotime(date('Y-m-d 23:59:59'));
		
		//get all accounts
		$sql = "select account_id, email, days_keep_rendered_film, mail_txt_delete_film  from account";
		if ($res =  $this->_dbconn->query($sql)) {
			while ($ds = mysqli_fetch_array($res)) {
				if((int)$ds['days_keep_rendered_film'] > 0) {
					
					//0 = unlimited => only check for > 0
					$sql_film = "select creation_time, name, description, flv_url from film where account_id = " . (int)$ds['account_id'];
					if ($res_film =  $this->_dbconn->query($sql_film)) {
						while ($ds_film = mysqli_fetch_array($res_film)) {
							//for sure init
							$diffTime = 0;
							$daysCount = 0;
							$creation_ts = strtotime($ds_film['creation_time']);
							$diffTime = $today_ts - $creation_ts;
							$daysCount = floor($diffTime/86400);
							$deadline = (int)$ds['days_keep_rendered_film'] + (int)DAYS_WAIT_DELETE_AFTER_MAIL;
							if((int)$daysCount > (int)$deadline) {
								if('' != $ds_film['flv_url']) {
									$delete_dir = BASE_DIR . $ds_film['flv_url'];
									//unlink();
//TODO delete aktivieren
echo '<br/>delete: ', $delete_dir; 
								}
							}
						}//while film
					} else {
						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->deleteOldFilmsByAccount read films'),$this->_dbconn->error);
						$success = false;
					}
				}//>0
   			}//while account
		} else {
			$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->deleteOldFilmsByAccount read accounts'),$this->_dbconn->error);
			$success = false;
		}
		return $success; 
	}
	
	public function getProductAssociations($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getProductAssociations']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			//TODO: klären, ob hier noch geprüft werden muss, ob Abhängigkeiten bestehen			
			$sql = "SELECT product_id FROM xref_account_group_product WHERE account_group_id = ".(int)$valueArr['accountGroupID'];
				    
			if ($res = $this->_dbconn->query($sql)) {
				return $res;
				}
			else
			{
				$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->getProductAssociations'),$this->_dbconn->error);
				return null;
			}
		}
	}
	
	public function getGroupAssociations($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getGroupAssociations']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			//TODO: klären, ob hier noch geprüft werden muss, ob Abhängigkeiten bestehen			
			$sql = "SELECT account_group_id FROM xref_account_group_product WHERE product_id = ".(int)$valueArr['productID'];
				    
			if ($res = $this->_dbconn->query($sql)) {
				return $res;
				}
			else
			{
				$this->_error = error::buildErrorString(array('SQL-ERROR'=>'account->getGroupAssociations'),$this->_dbconn->error);
				return null;
			}
		}
	}
	
	/**
	 * getAccountGroupList
	 * 
	 *
	 * @return result-set or null
	 */
	public function getAccountGroupList() {
        $sql="select"
            ." ag.* "
        ." from"
            ." account_group AS ag"
        ." where delete_ts IS NULL";

        return $this->dbSelect($sql, ['SQL-ERROR'=>'account->getAccountGroupList']);
	}
		
	/**
	 * getAccountList
	 * 
	 *
	 * @return result-set or null
	 */
	public function getAccountList() {
		$sql = "SELECT
		        a.*
		    FROM account AS a
		    WHERE delete_ts IS NULL
		    ORDER BY
                a.login_username";

        return $this->dbSelect($sql, ['SQL-ERROR' => 'account->getAccountList']);
	}
	
	public function getStatisticsA($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getStatistics']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$account_id = (int)$valueArr['AccountID'];
			
			if ($account_id >=0)
			{
				$sql = "SELECT asset_date, asset_timestamp"
					  .",SUM(asset_type = 'film') as filmCount"
					  .",SUM(asset_type = 'clip') as clipCount"
					  .",SUM(asset_type = 'motif') as motifCount"
					  ." FROM history"
					  ." WHERE account_account_id = ".(int)$valueArr['AccountID']
					  ." AND asset_timestamp >= ".(int)$valueArr['StartDate']
					  ." AND asset_timestamp <= ".(int)$valueArr['EndDate']
					  ." AND (asset_type = 'film' OR (asset_type = 'clip' AND clip_isDicative=0))"
					  ." group by asset_date"
					  .";";
			}
			else
			{
				$sql = "SELECT asset_date, asset_timestamp"
					  .",SUM(asset_type = 'film') as filmCount"
					  .",SUM(asset_type = 'clip') as clipCount"
					  ." FROM history"
					  ." WHERE asset_timestamp >= ".(int)$valueArr['StartDate']
					  ." AND asset_timestamp <= ".(int)$valueArr['EndDate']
					  ." AND (asset_type = 'film' OR (asset_type = 'clip' AND clip_isDicative=0))"
					  ." group by asset_date"
					  .";";			
			}
			
			
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
				return null;
			}

			return $res;
		}
	}
	
	public function getStatisticsB($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getStatistics']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$account_id = (int)$valueArr['AccountID'];
			
			$sql = "SELECT product.name"
				.",SUM(history.asset_type = 'clip') as clipCount"
				." FROM history, product"		
				." WHERE history.asset_type = 'clip' and product.product_id = history.asset_id"
  			    ." AND (asset_type = 'film' OR (asset_type = 'clip' AND clip_isDicative=0))"
				." AND asset_timestamp >= ".(int)$valueArr['StartDate']
				." AND asset_timestamp <= ".(int)$valueArr['EndDate'];				
			
			if ($account_id >= 0)
				$sql .= " AND account_account_id = ".(int)$valueArr['AccountID'];
				
			$sql .= " GROUP BY history.asset_id"
				  ." ORDER BY history.asset_id"
				  .";";
				
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}
	
	public function getStatisticsC($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['getStatistics']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$account_id = (int)$valueArr['AccountID'];
			
			$sql = "SELECT SUM(history.asset_type = 'clip') as clipCount"
				.",SUM(history.asset_type = 'film') as filmCount"
				." FROM history";
			
			if ($account_id >= 0)
			{
				$sql .= " WHERE account_account_id = ".(int)$valueArr['AccountID'];				
				$sql .= " AND (asset_type = 'film' OR (asset_type = 'clip' AND clip_isDicative=0))";
			}
			else
				$sql .= " WHERE (asset_type = 'film' OR (asset_type = 'clip' AND clip_isDicative=0))";
				
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
				return null;
			}
			return $res;
		}
	}
	
	public function exportStatistics($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['exportStatistics']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			$account_id = (int)$valueArr['AccountID'];
			$startDate = 0;
			$endDate = 32472226800;
			
			$output = "";
			
			if (isset($valueArr['StartDate']))
				$startDate = $valueArr['StartDate'];
			
			if (isset($valueArr['EndDate']))
				$endDate = $valueArr['EndDate'];
			
			//create output file
			$user_dir = UPLOAD_DIR . "statistics";
			if ($account_id >= 0)				
				$fp = fopen($user_dir."/joblog_".$account_id.".csv", "w+");
			else
				$fp = fopen($user_dir."/joblog_0.csv", "w+");
				
			$output .= "Report date:;".date("d.m.y - H:i", time()).";Date Range:".date("d.m.y - H:i",$startDate)." to ".date("d.m.y - H:i",$endDate)."\r\n";
			$output .= "\r\n";
			$output .= "Total Films;Total Clips\r\n";
			
			if ($account_id >= 0)
			{		
				$sql = "SELECT"
						." (SELECT sum(asset_type = 'film') FROM history where account_account_id = ".$account_id.") as allProductions,"
						." (SELECT sum(asset_type = 'clip') FROM history where account_account_id = ".$account_id." AND clip_isDicative = 0) as allJobs,"
						." (SELECT sum(asset_type = 'film') FROM history where account_account_id = ".$account_id." AND asset_timestamp >= ".$startDate." AND asset_timestamp <= ".$endDate.") as someProductions,"
						." (SELECT sum(asset_type = 'clip') FROM history where account_account_id = ".$account_id." AND clip_isDicative = 0 AND asset_timestamp >= ".$startDate." AND asset_timestamp <= ".$endDate.") as someJobs";
			}
			else
			{
				$sql = "SELECT"
						." (SELECT sum(asset_type = 'film') FROM history) as allProductions,"
						." (SELECT sum(asset_type = 'clip') FROM history WHERE clip_isDicative = 0) as allJobs,"
						." (SELECT sum(asset_type = 'film') FROM history where asset_timestamp >= ".$startDate." AND asset_timestamp <= ".$endDate.") as someProductions,"
						." (SELECT sum(asset_type = 'clip') FROM history, product WHERE clip_isDicative = 0 AND asset_timestamp >= ".$startDate." AND asset_timestamp <= ".$endDate.") as someJobs";
			}
				
			//fwrite ($fp, $sql."\r\n");
			
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
				return null;
			}
			
			$ds = mysqli_fetch_array($res);
			
			$output .= $ds['allProductions'].";".$ds['allJobs']."\r\n";
			$output .= "Films in date range;Clips in date range\r\n";
			$output .= $ds['someProductions'].";".$ds['someJobs']."\r\n";
			$output .= "Production Date;Production Name;Account Name;Clip Counter;Clip Name;Motif Counter;Motif Typ;Motif Name\r\n";
			
			$sql = "select"
					." history.asset_id"
					.",history.asset_timestamp"
					.",history.account_account_id"
					.",history.name as film_name"
					.",account.login_username"
					." FROM history, account"
					." WHERE asset_type = 'film'"
					." AND asset_timestamp >= ".$startDate." AND asset_timestamp <= ".$endDate
					." AND account.account_id = history.account_account_id";
				
			if ($account_id >= 0)
			{
				$sql .= " AND history.account_account_id = ".$account_id;
			}
			
			$sql .=" ORDER BY asset_timestamp";
				
			//$output .= $sql."\r\n";
			
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
				return null;
			}

			while ($ds = mysqli_fetch_array($res)) {
				$film_id = $ds['asset_id'];
				$film_date = $ds['asset_timestamp'];
				$account_name = $ds['login_username'];
				
				$motif_position = 9;				
				$clip_position = 0;
				
				//old entry
				if (intval($film_id) < 0)
				{						
					$output .= date("d.m.y H:i", $film_date).";".$ds['film_name'].";".$account_name.";;;;;\r\n";

					$sql2 = "SELECT"
							." *"
							." FROM history"
							." WHERE (asset_type = 'clip' OR asset_type = 'motif')"
							." AND asset_timestamp = ".$film_date;
					
					if (intval($account_id) >= 0)
						$sql2 .=" AND account_account_id = ".$account_id;
							
					//$output .= $sql2."\r\n";

					if (!$res2 =  $this->_dbconn->query($sql2)) {
						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
						return null;
					}
					
					while ($ds2 = mysqli_fetch_array($res2)) {
						if ($ds2['asset_type'] == "clip")
						{
							$clip_position += 1;
							$motif_position = 1;
							//get product name
							$sql3 = "select name from product where product_id = ".(int)$ds2['asset_id'];
							
							//$output .= $sql3."\r\n";
							
							if (!$res3 =  $this->_dbconn->query($sql3)) {
								$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
								return null;
							}
							
							$ds3 = mysqli_fetch_array($res3);
							$product_name = $ds3['name'];
							
							$output .= ";;;".$clip_position.";".$product_name.";;;;;\r\n";
						}
						else
						{
							$motif_position = +1;

							$output .=  ";;;;;".$motif_position.";N/A;N/A\r\n";
							
							$position = $position+1;
						}
					}
				}
				//new entry
				else
				{
					//get film data
					$sql2 = "select"
						." film.name"
						.",film.update_ts"
						.",film.custom2"
						.",account.login_username"
						." from film, account"
						." where film.film_id = ".$film_id
						." and account.account_id = film.account_id";
					
					//$output .= $sql2."\r\n");
					
					if (!$res2 =  $this->_dbconn->query($sql2)) {
						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
						return null;
					}
					
					$ds2 =  mysqli_fetch_array($res2);
					
					$output .= $ds2['update_ts'].";".$ds2['name'].";".$ds2['login_username'].";;;;\r\n";
					
					$sql2 = "select" 
						." xr.job_job_id,"
						." p.name as product_name,"
						." xr.position,"
						." xr.product_content_product_content_id,"
						." pc.content_format_id as cf_id,"
						." cf.name as content_name,"
						." motif.name as motif_name"
						." from motif, xref_production_product_content xr"
						." LEFT join product_content pc on (pc.product_content_id = xr.product_content_product_content_id)"
						." LEFT join content_format cf on (cf.content_format_id = pc.content_format_id)"
						." LEFT join product p on (p.product_id = pc.product_id)"
						." LEFT join film f on (f.production_id = xr.job_production_production_id)"
						." where f.film_id = ".$film_id
						." and motif.motif_id = xr.real_content_id"
						." ORDER BY xr.job_job_id, xr.position";
						
					//$output .= $sql2."\r\n");

					if (!$res2 =  $this->_dbconn->query($sql2)) {
						$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
						return null;
					}
				
					$old_id = -1;
					$old_position = 0;
					$motif_position = 1;
					$clip_position = 0;
					
					while ($ds2 =  mysqli_fetch_array($res2)) 
					{
						$new_position = $ds2['position'];
						
						if (intval($new_position == 1))
						{
							$clip_position += 1;
							$motif_position = 1;
							$output .= ";;;".$clip_position.";".$ds2['product_name']."\r\n";
							$output .= ";;;;;1;".$ds2['content_name'].";".$ds2['motif_name']."\r\n";
						}
						else
						{
							$motif_position += 1;
							$output .= ";;;;;".$motif_position.";".$ds2['content_name'].";".$ds2['motif_name']."\r\n";
						}
						
						$old_position = $new_position;
					}
				}
			}
			
			$sql = "select"
					." product.name,"
					." count(asset_id) as cnt"
					." from history, product"
					." where asset_type = 'clip'"
					." and product.product_id = history.asset_id"
					." AND (product.is_dicative = 0 AND product.product_id = asset_id)"
					." AND asset_timestamp >= ".$startDate." AND asset_timestamp <= ".$endDate;
			
			//$output .= $sql."\r\n");
			
			if ($account_id >= 0)
				$sql .= " AND account_account_id = ".$account_id;
				
			$sql .= " GROUP BY asset_id"
					." ORDER BY asset_id";
				
			if (!$res =  $this->_dbconn->query($sql)) {
				$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getStatistics'),$this->_dbconn->error);
				return null;
			}
			
			$output .= "\r\nProduct;nClipS\r\n";
			while ($ds = mysqli_fetch_array($res)) {
				$output .= $ds['name'].";".$ds['cnt']."\r\n";
			}
		}
		
		fwrite($fp, utf8_decode($output));
		fclose($fp);
	}
	
	public function getFileExtensions()
	{
		$sql = "select * from media_format";
		
		if (!$res =  $this->_dbconn->query($sql)) {
			$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getFileExtensions'),$this->_dbconn->error);
			return null;
		}
		return $res;	
	}

	public function getContentFormats()
	{
		$sql = "SELECT *
                FROM content_format
                WHERE
                  content_format_id > 0
                  AND delete_ts IS NULL";
		
		if (!$res =  $this->_dbconn->query($sql)) {
			$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getContentFormats'),$this->_dbconn->error);
			return null;
		}
		return $res;	
	}
	
	public function adjustNamedObject($valueArr) {
		$success = false;
		//validate
		$success = format::check_params($valueArr, $this->_required['adjustNamedObject']);
		if($success === false) {
			$this->_error .= error::buildErrorString(format::$_errorArr);
			return null;
		} else {
			//get validated/modified values
			$valueArr = format::$_returnArr;
			
			if (is_numeric($valueArr['Name']))
			{
				$sql = "SELECT name FROM product WHERE product_id = ".$valueArr['Name'];
				
				if (!$res =  $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getContentFormats'),$this->_dbconn->error);
					return null;
				}
				
				$ds = mysqli_fetch_array($res);
				
				$name = $ds['name'];
				
				$sql = "UPDATE history SET name ='".$name."' WHERE asset_timestamp = ".$valueArr['DateSent']." AND asset_type = 'clip'";
				
				if (!$res =  $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getContentFormats'),$this->_dbconn->error);
					return null;
				}
			}
			else
			{
				$name = $valueArr['Name'];
				$sql = "UPDATE history SET name ='".$name."' WHERE asset_timestamp = ".$valueArr['DateSent']." AND asset_type = 'film'";
				
				if (!$res =  $this->_dbconn->query($sql)) {
					$this->_error .= error::buildErrorString(array('SQL-ERROR'=>'account->getContentFormats'),$this->_dbconn->error);
					return null;
				}
			}	
		}

		return $this->_dbconn->affected_rows." -- ".$sql;
	}

    public function setMaintain($valueArr) {
        $success = format::check_params($valueArr, $this->_required['setMaintain']);
        if ($success === false) {
            $this->_error .= error::buildErrorString(format::$_errorArr);
            return null;
        }

        $status = (int)!!$valueArr['status'];
        $sql = "UPDATE settings SET value='{$status}' WHERE name='maintain';";

        if (!$res =  $this->_dbconn->query($sql)) {
            $this->_error .= error::buildErrorString(array('SQL-ERROR' => 'set maintain error'), $this->_dbconn->error);
            return null;
        }

        return true;
    }
}