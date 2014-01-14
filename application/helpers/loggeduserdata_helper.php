<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function loggedUserData()
{
    $ci=& get_instance();
    $ci->load->model('um_users_model','',TRUE);
    $ci->load->library('user_manager');
    $username = $ci->user_manager->this_user_name();
    $data = $ci->um_users_model->get_user_details($username);
    return $data;
}

function renderName()
{
    $ci=& get_instance();
    $ci->load->model('um_users_model','',TRUE);
    $ci->load->library('user_manager');
    $username = $ci->user_manager->this_user_name();
    $data = $ci->um_users_model->get_user_details($username);
    return $data['firstname'] . ' ' . $data['lastname'];
}
?>