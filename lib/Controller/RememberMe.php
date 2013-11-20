<?php
/**
 * Created by JetBrains PhpStorm.
 * User: vadym
 * Date: 11/20/13
 * Time: 6:57 AM
 * To change this template use File | Settings | File Templates.
 */
namespace rvadym\remember_me;
class Controller_RememberMe extends \AbstractController{
    function init(){
        parent::init();
        //var_dump($_COOKIE);
        $this->api->requires('atk','4.2');

        if(!$this->owner instanceof \Auth_Basic){
            throw $this->exception('Must be added into $api->auth');
        }

        if(!$this->owner->model->hasField('remember_me_hash')){
            throw $this->exception('Add remember_me_hash field to your login model.');
        }

        $this->owner->addHook(array('check','loggedIn','logout','updateForm'),$this);
    }
    function check($auth){
//        if(isset($_COOKIE[$auth->name."_username"]) && isset($_COOKIE[$auth->name."_password"])){
//
//            $id=$auth->verifyCredentials( $_COOKIE[$auth->name."_username"], $_COOKIE[$auth->name."_password"]);
//            if($id){
//                // Successfully validated user
//                $this->breakHook($id);
//            }
//        }


        $this->api->auth->model->tryLoadBy();
    }
    function loggedIn($auth,$user=null,$pass=null){
//        if(!$pass)return;
//        if(!$auth->form->get('memorize'))return;
//
//        setcookie($auth->name."_username",$user,time()+60*60*24*30*6);
//        setcookie($auth->name."_password",$pass,time()+60*60*24*30*6);
    }
    function logout($auth){
//		setcookie($auth->name."_username",null);
//		setcookie($auth->name."_password",null);
    }
    function updateForm($auth){
		$auth->form->addField('Checkbox','memorize','Remember me');
    }




    public $login_hash_name        = '_login_hash';
    function generateLoginHash($salt){
        $hash = hash('md5',$salt.microtime(true));
        return $hash;
    }
    function rememberLoginHash($hash,$generate=false) {
        if ($generate) {
            $salt = $hash;
            $hash = $this->generateLoginHash($salt);
        }
        setcookie($this->api->auth->name.$this->login_hash_name,$hash,time()+60*60*24*30*6);
        return $hash;
    }
    function getLoginHash() {
        return $_COOKIE[$this->api->auth->name.$this->login_hash_name];
    }
    function forgetLoginHash() {
        setcookie($this->api->auth->name.$this->login_hash_name,null,time()+60*60*24*30*6);
    }
}
