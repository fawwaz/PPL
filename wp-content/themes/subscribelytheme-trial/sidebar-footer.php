<?php
if (!is_active_sidebar('first-footer-widget-area') && !is_active_sidebar('second-footer-widget-area') && !is_active_sidebar('third-footer-widget-area') && !is_active_sidebar('fourth-footer-widget-area')) {
$str = '<style type="text/css">';
$str .= '#footer_wrapper{padding:0;}';
$str .= '</style>';
print $str;
} 
?>
<footer class="footer">
    <div class="grid_6 alpha">
        <div class="footer_widget">
            <?php if (is_active_sidebar('first-footer-widget-area')) : ?>
                <?php dynamic_sidebar('first-footer-widget-area'); ?>             
            <?php endif; ?>
        </div>
    </div>
    <div class="grid_6">
        <div class="footer_widget">
            <?php if (is_active_sidebar('second-footer-widget-area')) : ?>
                <?php dynamic_sidebar('second-footer-widget-area'); ?>          
            <?php endif; ?>
        </div>
    </div>
    <div class="grid_6">
        <div class="footer_widget">
            <?php if (is_active_sidebar('third-footer-widget-area')) : ?>
                <?php dynamic_sidebar('third-footer-widget-area'); ?>       
            <?php endif; ?>
        </div>
    </div>
    <div class="grid_6 omega last">
        <div class="footer_widget">
            <?php if (is_active_sidebar('fourth-footer-widget-area')) : ?>
                <?php dynamic_sidebar('fourth-footer-widget-area'); ?>        
            <?php endif; ?>
        </div>
    </div>
</footer>