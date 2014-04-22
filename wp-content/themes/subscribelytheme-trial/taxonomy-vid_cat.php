<?php
get_header();
sc_bred_crumbs();
sc_before_content();
?>
<!--Start Content Wrapper-->
<div id="content_wrapper">
    <div class="fullwidth">
        <h1 class="title"><?php printf(__('Category Archives&nbsp;&nbsp;:&nbsp;&nbsp;%s',THEME_SLUG), '' . single_cat_title('', false) . ''); ?></h1>
        <div class="border_strip"></div>
        <?php sc_thumbnails(); ?>  
        <div class="clear"></div>
        <?php sc_nav(); ?>
    </div>    
</div>
<!--End Content Wrapper-->
<?php
sc_after_content();
get_footer();
?>