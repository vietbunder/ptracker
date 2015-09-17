<?php

class AccountMember extends CFormModel { 
    
    // Atrributes for Account
    public $username;
    public $password;
    public $retype_password;
    
    // Attributes for Member
    public $name;
    public $department;
    public $role;
    
    // Applied rules for validation 
    public function rules() { 
        return array(
            array('username, password, retype_password, name, department, role', 'required'),
            array('username', 'length', 'max'=>25),
            array('password', 'length', 'max'=>255),
            array('retype_password', 'compare', 'compareAttribute'=>'password'),
            array('name, department, role', 'length', 'max'=>64),
            array('username, password, retype_password, name, department, role', 'safe'),
        );
    } 
    // sets attribute labels for view labeling 
    public function attributeLabels() {
        return array(
            'username'=>'Username',
            'password'=>'Password',
            'retype_password'=>'Re-type Password',
            'name'=>'Name',
            'department'=>'Department',
            'role'=>'Role',
        );
    }
} 
?> 

