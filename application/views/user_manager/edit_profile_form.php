<?php $this->load->view('user_manager/header');?>
    <div class="container">
<?php
$this->load->helper('form');
$this->load->helper('um');
echo $msg;
?>
<form action="editprofile" method="post" enctype="multipart/form-data">
<table>
<tr>
<td>Username</td>
<td><?php echo $username; ?></td>
<td></td>
</tr>
<tr>
<td>New Password</td>
<td><input type="password" name="password" value="" /></td>
<td><?php echo form_error('password'); ?></td>
</tr>
<tr>
<td>New Password again</td>
<td><input type="password" name="password2" value="" /></td>
<td><?php echo form_error('password2'); ?></td>
</tr>
<tr>
<td>profile picture</td>
<td><img src="<?php echo base_url().$dp; ?>_thumb.jpg" /></td>
<td></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><input type="file" name="dp" /></td>
<td><?php echo form_error('dp'); ?></td>
</tr>
<tr>
<td>&nbsp;</td>
<td><label><input type="checkbox" name="deletedp" />Delete my profile picture</label></td>
<td>&nbsp;</td>
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
<td><?php echo form_dropdown('country',$country_list,$country); ?></td>
<td><?php echo form_error('country'); ?></td>
</tr>
<tr>
<td>Address</td>
<td><input type="text" name="address" value="<?php echo $address; ?>" /></td>
<td><?php echo form_error('address'); ?></td>
</tr>
<tr>
<td>Interests</td>
<td><input type="text" name="interests" value="<?php echo $interests; ?>" /></td>
<td><?php echo form_error('interests'); ?></td>
</tr>
<tr>
<td>Profile privacy</td>
<td>
<label>
<input type="radio" name="profileprivacy" value="private" <?php set_radio_state($profileprivacy, 'private'); ?> />private
</label>
<label>
<input type="radio" name="profileprivacy" value="public" <?php set_radio_state($profileprivacy, 'public'); ?> />public
</label>
</td>
<td><?php echo form_error('profileprivacy'); ?></td>
</tr>
<tr>
<td>Make me online</td>
<td>
<label><input type="radio" name="appearonline" value="1" <?php set_radio_state($appearonline, '1'); ?> />Yes</label>
<label><input type="radio" name="appearonline" value="0" <?php set_radio_state($appearonline, '0'); ?> />No</label>
</td>
<td><?php echo form_error('appearonline'); ?></td>
</tr>
<tr>
<td></td>
<td><input type="submit" name="submit" value="submit" /></td>
<td></td>
</tr>
</table>
</form>
<a href="<?php echo base_url();?>profile">my profile</a>
</div>
<?php $this->load->view('user_manager/footer');?>