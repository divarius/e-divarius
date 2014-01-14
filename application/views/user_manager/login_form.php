<?php $this->load->view('user_manager/header');?>
<form class="form-signin" action="ingresar" method="POST">
    <div class="container">
            <div class="logo"></div>
            <input class="input-block-level icon-user" placeholder="<?php echo $this->lang->line('um_username'); ?>" name="username" type="text" value="<?php echo $username ?>" />
                <?php echo form_error('username'); ?>
            <input class="input-block-level icon-pass" placeholder="<?php echo $this->lang->line('um_password'); ?>" name="password" type="password" value="" />
                <?php echo form_error('password'); ?>
            <input class="btn btn-large btn-primary" type="submit" value="Ingresar" name="login">
            <p><a href="<?php echo base_url();?>resetear-password"><?php echo $this->lang->line('um_forgot_password'); ?></a></p>
            <p><a href="<?php echo base_url();?>registracion"><?php echo $this->lang->line('um_registration'); ?></a></p>
    </div>
</form>
<?php $this->load->view('user_manager/footer');?>