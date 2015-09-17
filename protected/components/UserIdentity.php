<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
    private $_id;
    
    // override the CBaseUserIdentity::getId() 
    // why should the method be overridden? 
    // in CBaseUserIdentity, this method returns username, 
    // in our case it shouldn't so we need to override it. 
    public function getId() { 
        return($this->_id);
    }
    
    public function authenticate() { 
        // find the account by its username 
        $account = Account::model()->findByAttributes(array( 
            'username' => $this->username, 
            )); 
        
        // tests the given password against account's 
        if ($account && $account->comparePassword($this->password)) { 
            // when it is successful, set the id with account's 
            $this->_id = $account->id; 
            // as it is a successful test, no erroroccurs 
            $this->errorCode = self::ERROR_NONE; 
            // returns the validation summary as TRUE 
            return (TRUE); 
        }
        
        // this two codes will only be executed when above test fails 
        // set the error as unknown membership 
        // and returns a FALSE value indicating a failed authentication 
        $this->errorCode = self::ERROR_UNKNOWN_IDENTITY; 
        return (FALSE); 
    }
}