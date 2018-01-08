<?php

FLTheme::fixed_header();

do_action( 'fl_before_top_bar' );

FLTheme::top_bar();

do_action( 'fl_after_top_bar' );
do_action( 'fl_before_header' );

FLTheme::header_layout();

do_action( 'fl_after_header' );
