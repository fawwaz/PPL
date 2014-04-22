<?php

function sc_front_header() {
    ?>
    <div class="top-strip"></div>
    <!--Start Slider Wrapper-->
    <div id="slider_wrapper">
        <div class="container_24">
            <div class="grid_24">
                <div class="flexslider">
                    <ul class="slides">
                        <!--Start First Slider-->
                        <li>
                            <!--Start Entry Wrapper-->
                            <div id="entry_wrapper">
                                <div class="grid_10 alpha">
                                    <!--Start Entry-->
                                    <div class="entry">
                                        <h1 class="entry_heading">
                                            <?php
                                            if (inkthemes_get_option('inkthemes_heading_1') != '') {
                                                echo inkthemes_get_option('inkthemes_heading_1');
                                            } else {
                                                echo 'Learn Yoga through our Step by Step Video Series.';
                                            }
                                            ?>
                                        </h1>
                                        <h4 class="entry_content">
                                            <?php
                                            if (inkthemes_get_option('inkthemes_des_1') != '') {
                                                echo inkthemes_get_option('inkthemes_des_1');
                                            } else {
                                                echo 'The best Yoga Series on all Internet. Become Member Today for just $15 PM.';
                                            }
                                            ?>
                                        </h4>
                                        <?php
                                        if (inkthemes_get_option('inkthemes_btn_text_1') != '') {
                                            echo '<a class="entry_btn" href="' . inkthemes_get_option('inkthemes_btn_link_1') . '"><span class="btn_left"></span><span class="btn_right">' . inkthemes_get_option('inkthemes_btn_text_1') . '</span></a>';
                                        } else {
                                            echo '<a class="entry_btn" href="#"><span class="btn_left"></span><span class="btn_right">Subscribe Now</span></a>';
                                        }
                                        ?>
                                    </div>
                                    <!--End Entry-->
                                </div>
                                <div class="grid_14 omega">
                                    <!--Start Vid content-->
                                    <div class="vid_content">                                      

                                        <div class="vid">
                                            <?php
                                            $mystring1 = inkthemes_get_option('inkthemes_video_content_1');
                                            $value_img = array('.jpg', '.png', '.jpeg', '.gif', '.bmp', '.tiff', '.tif');
                                            $check_img_ofset = 0;
                                            foreach ($value_img as $get_value) {
                                                if (preg_match("/$get_value/", $mystring1)) {
                                                    $check_img_ofset = 1;
                                                }
                                            }
                                            if ($check_img_ofset == 0 && inkthemes_get_option('inkthemes_video_content_1') != '') {
                                                echo inkthemes_get_option('inkthemes_video_content_1');
                                            } else {
                                                if (inkthemes_get_option('inkthemes_video_content_1') != '') {
                                                    ?>
                                                    <img class="slider_img" src="<?php echo inkthemes_get_option('inkthemes_video_content_1'); ?>" alt="slider image" />
                                                <?php } else { ?>
                                                    <iframe width="560" height="315" src="http://www.youtube.com/embed/Pw71TJW_Hwg" frameborder="0" allowfullscreen></iframe>
                                                <?php }
                                            } ?>
                                        </div>
                                    </div>
                                    <!--End Vid Content-->
                                </div>
                            </div>
                            <!--End Entry Wrapper-->
                        </li>
                        <!--End First Slider-->
                        <?php
                        if (inkthemes_get_option('inkthemes_heading_2') != '') {
                            ?>
                        <!--Start Second Slider-->
                            <li>
                                <!--Start Entry Wrapper-->
                                <div id="entry_wrapper">
                                    <div class="grid_10 alpha">
                                        <!--Start Entry-->
                                        <div class="entry">
                                            <h1 class="entry_heading">
                                                <?php
                                                if (inkthemes_get_option('inkthemes_heading_2') != '') {
                                                    echo inkthemes_get_option('inkthemes_heading_2');
                                                }
                                                ?>
                                            </h1>
                                            <h4 class="entry_content">
                                                <?php
                                                if (inkthemes_get_option('inkthemes_des_2') != '') {
                                                    echo inkthemes_get_option('inkthemes_des_2');
                                                }
                                                ?>
                                            </h4>
                                            <?php
                                            if (inkthemes_get_option('inkthemes_btn_text_2') != '') {
                                                echo '<a class="entry_btn" href="' . inkthemes_get_option('inkthemes_btn_link_2') . '"><span class="btn_left"></span><span class="btn_right">' . inkthemes_get_option('inkthemes_btn_text_2') . '</span></a>';
                                            }
                                            ?>
                                        </div>
                                        <!--End Entry-->
                                    </div>
                                    <div class="grid_14 omega">
                                        <!--Start Vid content-->
                                        <div class="vid_content">
                                            <div class="vid">
                                                <?php
                                                $mystring2 = inkthemes_get_option('inkthemes_video_content_2');
                                                $value_img = array('.jpg', '.png', '.jpeg', '.gif', '.bmp', '.tiff', '.tif');
                                                $check_img_ofset = 0;
                                                foreach ($value_img as $get_value) {
                                                    if (preg_match("/$get_value/", $mystring2)) {
                                                        $check_img_ofset = 1;
                                                    }
                                                }
                                                if ($check_img_ofset == 0 && inkthemes_get_option('inkthemes_video_content_2') != '') {
                                                    echo inkthemes_get_option('inkthemes_video_content_2');
                                                } else {
                                                    if (inkthemes_get_option('inkthemes_video_content_2') != '') {
                                                        ?>
                                                        <img class="slider_img" src="<?php echo inkthemes_get_option('inkthemes_video_content_2'); ?>" alt="slider image" />
                                                <?php }
                                            } ?>
                                            </div>
                                        </div>
                                        <!--End Vid Content-->
                                    </div>
                                </div>
                                <!--End Entry Wrapper-->
                            </li>
                            <!--End Second Slider-->
                        <?php } ?>
                                          <?php
                        if (inkthemes_get_option('inkthemes_heading_3') != '') {
                            ?>
                            <!--Start Third Slider-->
                            <li>                                
                                <!--Start Entry Wrapper-->
                                <div id="entry_wrapper">
                                    <div class="grid_10 alpha">
                                        <!--Start Entry-->
                                        <div class="entry">
                                            <h1 class="entry_heading">
                                                <?php
                                                if (inkthemes_get_option('inkthemes_heading_3') != '') {
                                                    echo inkthemes_get_option('inkthemes_heading_3');
                                                }
                                                ?>
                                            </h1>
                                            <h4 class="entry_content">
                                                <?php
                                                if (inkthemes_get_option('inkthemes_des_3') != '') {
                                                    echo inkthemes_get_option('inkthemes_des_3');
                                                }
                                                ?>
                                            </h4>
                                            <?php
                                            if (inkthemes_get_option('inkthemes_btn_text_3') != '') {
                                                echo '<a class="entry_btn" href="' . inkthemes_get_option('inkthemes_btn_link_3') . '"><span class="btn_left"></span><span class="btn_right">' . inkthemes_get_option('inkthemes_btn_text_3') . '</span></a>';
                                            }
                                            ?>
                                        </div>
                                        <!--End Entry-->
                                    </div>
                                    <div class="grid_14 omega">
                                        <!--Start Vid content-->
                                        <div class="vid_content">
                                            <div class="vid">
                                                <?php
                                                $mystring3 = inkthemes_get_option('inkthemes_video_content_3');
                                                $value_img = array('.jpg', '.png', '.jpeg', '.gif', '.bmp', '.tiff', '.tif');
                                                $check_img_ofset = 0;
                                                foreach ($value_img as $get_value) {
                                                    if (preg_match("/$get_value/", $mystring3)) {
                                                        $check_img_ofset = 1;
                                                    }
                                                }
                                                if ($check_img_ofset == 0 && inkthemes_get_option('inkthemes_video_content_3') != '') {
                                                    echo inkthemes_get_option('inkthemes_video_content_3');
                                                } else {
                                                    if (inkthemes_get_option('inkthemes_video_content_3') != '') {
                                                        ?>
                                                        <img class="slider_img" src="<?php echo inkthemes_get_option('inkthemes_video_content_3'); ?>" alt="slider image" />
                                                <?php }
                                            } ?>
                                            </div>
                                        </div>
                                        <!--End Vid Content-->
                                    </div>
                                </div>
                                <!--End Entry Wrapper-->
                            </li>
                            <!--End Third Slider-->
                        <?php } ?>
                                          <?php
                        if (inkthemes_get_option('inkthemes_heading_4') != '') {
                            ?>
                            <!--Start Fourth Slider-->
                            <li>                                
                                <!--Start Entry Wrapper-->
                                <div id="entry_wrapper">
                                    <div class="grid_10 alpha">
                                        <!--Start Entry-->
                                        <div class="entry">
                                            <h1 class="entry_heading">
                                                <?php
                                                if (inkthemes_get_option('inkthemes_heading_4') != '') {
                                                    echo inkthemes_get_option('inkthemes_heading_4');
                                                }
                                                ?>
                                            </h1>
                                            <h4 class="entry_content">
                                                <?php
                                                if (inkthemes_get_option('inkthemes_des_4') != '') {
                                                    echo inkthemes_get_option('inkthemes_des_4');
                                                }
                                                ?>
                                            </h4>
                                            <?php
                                            if (inkthemes_get_option('inkthemes_btn_text_4') != '') {
                                                echo '<a class="entry_btn" href="' . inkthemes_get_option('inkthemes_btn_link_4') . '"><span class="btn_left"></span><span class="btn_right">' . inkthemes_get_option('inkthemes_btn_text_4') . '</span></a>';
                                            }
                                            ?>
                                        </div>
                                        <!--End Entry-->
                                    </div>
                                    <div class="grid_14 omega">
                                        <!--Start Vid content-->
                                        <div class="vid_content">
                                            <div class="vid">
                                                <?php
                                                $mystring4 = inkthemes_get_option('inkthemes_video_content_4');
                                                $value_img = array('.jpg', '.png', '.jpeg', '.gif', '.bmp', '.tiff', '.tif');
                                                $check_img_ofset = 0;
                                                foreach ($value_img as $get_value) {
                                                    if (preg_match("/$get_value/", $mystring4)) {
                                                        $check_img_ofset = 1;
                                                    }
                                                }
                                                if ($check_img_ofset == 0 && inkthemes_get_option('inkthemes_video_content_4') != '') {
                                                    echo inkthemes_get_option('inkthemes_video_content_4');
                                                } else {
                                                    if (inkthemes_get_option('inkthemes_video_content_4') != '') {
                                                        ?>
                                                        <img class="slider_img" src="<?php echo inkthemes_get_option('inkthemes_video_content_4'); ?>" alt="slider image" />
                                                <?php }
                                            } ?>
                                            </div>
                                        </div>
                                        <!--End Vid Content-->
                                    </div>
                                </div>
                                <!--End Entry Wrapper-->
                            </li>
                            <!--End Fourth Slider-->
                        <?php } ?>
                                          <?php
                        if (inkthemes_get_option('inkthemes_heading_5') != '') {
                            ?>
                            <!--Start Fifth Slider-->
                            <li>                                
                                <!--Start Entry Wrapper-->
                                <div id="entry_wrapper">
                                    <div class="grid_10 alpha">
                                        <!--Start Entry-->
                                        <div class="entry">
                                            <h1 class="entry_heading">
                                                <?php
                                                if (inkthemes_get_option('inkthemes_heading_5') != '') {
                                                    echo inkthemes_get_option('inkthemes_heading_5');
                                                }
                                                ?>
                                            </h1>
                                            <h4 class="entry_content">
                                                <?php
                                                if (inkthemes_get_option('inkthemes_des_5') != '') {
                                                    echo inkthemes_get_option('inkthemes_des_5');
                                                }
                                                ?>
                                            </h4>
                                            <?php
                                            if (inkthemes_get_option('inkthemes_btn_text_5') != '') {
                                                echo '<a class="entry_btn" href="' . inkthemes_get_option('inkthemes_btn_link_5') . '"><span class="btn_left"></span><span class="btn_right">' . inkthemes_get_option('inkthemes_btn_text_5') . '</span></a>';
                                            }
                                            ?>
                                        </div>
                                        <!--End Entry-->
                                    </div>
                                    <div class="grid_14 omega">
                                        <!--Start Vid content-->
                                        <div class="vid_content">
                                            <div class="vid">
                                                <?php
                                                $mystring5 = inkthemes_get_option('inkthemes_video_content_5');
                                                $value_img = array('.jpg', '.png', '.jpeg', '.gif', '.bmp', '.tiff', '.tif');
                                                $check_img_ofset = 0;
                                                foreach ($value_img as $get_value) {
                                                    if (preg_match("/$get_value/", $mystring5)) {
                                                        $check_img_ofset = 1;
                                                    }
                                                }
                                                if ($check_img_ofset == 0 && inkthemes_get_option('inkthemes_video_content_5') != '') {
                                                    echo inkthemes_get_option('inkthemes_video_content_5');
                                                } else {
                                                    if (inkthemes_get_option('inkthemes_video_content_5') != '') {
                                                        ?>
                                                        <img class="slider_img" src="<?php echo inkthemes_get_option('inkthemes_video_content_5'); ?>" alt="slider image" />
                                                <?php }
                                            } ?>
                                            </div>
                                        </div>
                                        <!--End Vid Content-->
                                    </div>
                                </div>
                                <!--End Entry Wrapper-->
                            </li>
                            <!--End Fifth Slider-->
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <!--End Slider Wrapper-->
    <div class="clear"></div>
    <div class="bottom-strip"></div>
    <div class="clear"></div>
    <?php
}

add_action('sc_front_header_entry', 'sc_front_header', 1);

function sc_bredcrumbs() {
    ?>
    <div class="top-strip"></div>
    <!--Start Crumb Wrapper-->
    <div id="crumbs_wrapper">
        <div class="container_24">
            <div class="grid_24">
                <div class="crumb">
    <?php inkthemes_breadcrumbs(); ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <!--End Crumb Wrapper-->
    <div class="clear"></div>
    <div class="bottom-strip"></div>
    <div class="clear"></div>
    <?php
}

add_action('sc_bred_crumbs', 'sc_bredcrumbs', 1);

function sc_beforecontent() {
    ?>
    <!--Start Content container-->
    <div class="container_24">
        <div class="grid_24">
            <?php
        }

        add_action('sc_before_content', sc_beforecontent);

        function sc_aftercontent() {
            ?>
        </div>
    </div>
    <!--End Content Container-->
    <?php
}

add_action('sc_after_content', 'sc_aftercontent');
?>
