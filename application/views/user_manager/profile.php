<?php $this->load->view('user_manager/header');?>
    <div class="container">
    <?php
    $this->load->helper('date');
    ?>
    <table border="0">
    <tr>
    <td><?php echo $username ?><br/>
    <img src="<?php echo base_url().$dp;?>_thumb.jpg" /></td>
    <td>Profile privacy : <?php echo $profileprivacy ?><br>
    You are now : <?php echo $appearonline ?></td>
    <td>Country : <?php echo $country ?><br>
    Last login on <?php echo unix_to_human($lastloggenindate) ?>
    </td>
    </tr>
    <tr>
    <td>Full name on account</td>
    <td><?php echo $firstname ?> <?php echo $secondname ?> <?php echo $lastname ?></td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td>Date of birth</td>
    <td><?php echo $dateofbirth ?></td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td>Address</td>
    <td><?php echo $address ?></td>
    <td>&nbsp;</td>
    </tr>
    <tr>
    <td>account email</td>
    <td><?php echo $email ?></td>
    <td>Interests<br><?php echo $interests ?>
    </td>
    </tr>
    <tr>
    <td>You registered on </td>
    <td><?php echo unix_to_human($registereddate) ?></td>
    <td>&nbsp;</td>
    </tr>
    </table>
    <a href="<?php echo base_url();?>logout">logout</a>
    <?php
    if ($this->user_manager->this_user_name()==$username){
    echo '<a href="'. base_url().'editprofile">edit profile</a>';
    }
    if ($this->user_manager->is_this_admin()){
    echo '<a href="'. base_url().'editprofile/'.$username.'">edit this persons profile</a>';
    }
    ?>
    </div>
<?php $this->load->view('user_manager/footer');?>