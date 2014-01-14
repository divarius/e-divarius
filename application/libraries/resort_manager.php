<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Resort_manager {

    function __construct(){

    }

    //registers a user with the given data
    public function add_resort($dbdata)
    {
        $CI =& get_instance();
        return $CI->resort_model->add_resort($dbdata);
    }

    //updates user info
    public function update_resort_info($username, $dbdata){
        $CI =& get_instance();
        $CI->load->library('email');

        if ($CI->um_users_model->is_user_exist($username)) {
            $CI->um_users_model->update_user($username,$dbdata);
            return true;
        } else {
            return false;
        }
    }
}