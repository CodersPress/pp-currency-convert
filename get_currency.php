<?php
require_once('../../../wp-blog-header.php');
echo get_post_meta( $_POST['val'], 'currency', true );
?>