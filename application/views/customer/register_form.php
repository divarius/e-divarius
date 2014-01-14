<?php $this->load->view('user_manager/header');?>
    <div class="container">
<?php
$this->load->helper('form_helper');
echo $msg;
?>
<form action="registracion" method="post">
    <table>
        <tr>
            <td><?php echo 'username'; ?></td>
            <td><input type="text" name="username" value="<?php echo $username; ?>" /></td>
            <td><?php echo form_error('username'); ?></td>
        </tr>
            <tr>
            <td>Password</td>
            <td><input type="password" name="password" value="" /></td>
            <td><?php echo form_error('password'); ?></td>
        </tr>
        <tr>
            <td>Password again</td>
            <td><input type="password" name="password2" value="" /></td>
            <td><?php echo form_error('password2'); ?></td>
        </tr>
        <tr>
            <td>email</td>
            <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
            <td><?php echo form_error('email'); ?></td>
        </tr>
        <tr>
            <td>First name</td>
            <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" /></td>
            <td><?php echo form_error('firstname'); ?></td>
        </tr>
        <tr>
            <td>Second name</td>
            <td><input type="text" name="secondname" value="<?php echo $secondname; ?>" /></td>
            <td><?php echo form_error('secondname'); ?></td>
        </tr>
        <tr>
            <td>Last name</td>
            <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" /></td>
            <td><?php echo form_error('lastname'); ?></td>
        </tr>
        <tr>
            <td>Date of birth</td>
            <td><input type="text" name="dateofbirth" value="<?php echo $dateofbirth; ?>" /></td>
            <td><?php echo form_error('dateofbirth'); ?></td>
        </tr>
        <tr>
            <td>Country</td>
            <td><?php echo form_dropdown('country',$country_list,$default_country); ?></td>
            <td><?php echo form_error('country'); ?></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="submit" /></td>
            <td></td>
        </tr>
    </table>    
</form>
<a href="<?php echo base_url();?>login">login</a>
    </div>
<?php $this->load->view('user_manager/footer');?>