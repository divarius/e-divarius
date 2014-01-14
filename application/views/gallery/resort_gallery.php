<?php $this->load->view('gallery/header');?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>
<div class="container">
    <?php echo $output; ?>
</div>
<?php $this->load->view('gallery/footer');?>
