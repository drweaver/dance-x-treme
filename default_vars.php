<?php

$banner_list = array(
    'From beginners to advanced go with <span style=\"white-space: nowrap;\">Dance-X-Treme</span>',
    'Strictly fun - learn to dance with Sue and George',
    'Lets get social! Fun dance events to perfect your skills'
    );

// Templated variables that can be overridden
$t_title_append = '';
$t_description = '';
$t_altcss_inc = './empty_content.php';
$t_head_inc = './empty_content.php';
$t_banner = $banner_list[ rand( 0, count($banner_list) -1 ) ];
$t_banner_num = '1';
$t_content_inc = './empty_content.php';
$t_rightbox_inc = './right_box.php';
?>