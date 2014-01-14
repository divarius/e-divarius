<?php $this->load->view('user_manager/header');?>
    <div class="container">
    <?php
    echo $msg;
    ?>
        <form action="reset" method="post">
            <table>
                <tr>
                    <td>your email</td>
                    <td><input type="text" name="email" value="<?php echo $email ?>" /></td>
                    <td><?php echo form_error('email'); ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td><input type="submit" value="reset" name="submit" /></td>
                    <td></td>
                </tr>
            </table>
        </form>
        <a href="<?php echo base_url();?>login">login</a>
    </div>
<?php $this->load->view('user_manager/footer');?>