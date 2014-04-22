<div class="clear"></div>
<div class="footer_strip"></div>
<!--Start Footer Wrapper-->
<div id="footer_wrapper">
    <div class="container_24">
        <div class="grid_24">
            <?php get_sidebar('footer'); ?>
        </div>
    </div>
    <div class="clear"></div>
</div>
<!--End Footer Wrapper-->
<div class="clear"></div>
<!--Start Footer Bottom-->
<div id="footer_bottom">
    <div class="container_24">
        <div class="grid_24">
            <div class="grid_14 alpha">
                <div class="left_bottom_content">
                    <ul class="socials">
                        <?php if (inkthemes_get_option('inkthemes_twitter') != '') { ?>
                            <li><a target="_new" href="<?php echo inkthemes_get_option('inkthemes_twitter'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/twitter.png" alt="twitter" title="Tweet This"/></a></li>
                        <?php } ?>
                        <?php if (inkthemes_get_option('inkthemes_facebook') != '') { ?>
                            <li><a target="_new" href="<?php echo inkthemes_get_option('inkthemes_facebook'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/facebook.png" alt="facebook" title="Facebook"/></a></li>
                        <?php } ?>
                        <?php if (inkthemes_get_option('inkthemes_rss') != '') { ?>
                            <li><a target="_new" href="<?php echo inkthemes_get_option('inkthemes_rss'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/rss.png" alt="rss" title="Rss"/></a></li>
                        <?php } ?>
                        <?php if (inkthemes_get_option('inkthemes_google') != '') { ?>
                            <li><a target="_new" href="<?php echo inkthemes_get_option('inkthemes_google'); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/plus.png" alt="google plus" title="Google Plus"/></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
            <div class="grid_10 omega">
                <div class="right_bottom_content">
                    <?php
                    if (inkthemes_get_option('inkthemes_twitter') || inkthemes_get_option('inkthemes_twitter') || inkthemes_get_option('inkthemes_twitter') || inkthemes_get_option('inkthemes_twitter')) {
                        $class = 'copyright';
                    }
                    ?>
                    <?php if (inkthemes_get_option('inkthemes_footertext') != '') {
                        ?>
                    <p class="<?php echo $class; ?>"><?php echo inkthemes_get_option('inkthemes_footertext'); ?></p>
                        <?php
                    } else {
                        ?>
                        <p class="<?php echo $class; ?>">2013 &COPY; InkThemes. All rights reserved.</p>
                        <?php
                    }
                    ?>                             
                </div>
            </div>
        </div>
    </div>
    <div class="clear"></div>
</div>
<!--End Footer Bottom-->
<?php wp_footer(); ?>
</body>
</html>
