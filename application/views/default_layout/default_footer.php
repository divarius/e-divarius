</div>
    <footer>
        <figure><img src="<?=base_url()?>public/img/footer_logo.png"></figure>
    </footer> 
    <?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
    <?php endforeach; ?>
    <?php
        // Define JS
        $js = array(
            array('jquery/jquery.multiselect.min.js'),
            array('jquery/jquery.multiselect.filter.js'),
            array('jquery/jquery.multiselect.es.js'),
            array('jquery/chosen.jquery.min.js'),
            array('jquery/jquery.nicescroll.min.js')
        );
        // create group
        $this->carabiner->group('grupobase', array('js' => $js));           
        $this->carabiner->display('grupobase');
    ?>
    <script>
        $(document).ready(function() {
                //* show all elements & remove preloader
                setTimeout('$("html").removeClass("js")',1000);
        });
    </script>
</body>
</html>