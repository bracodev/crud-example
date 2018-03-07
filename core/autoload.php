<?php

function autoload_controllers( $file )
{
	if( file_exists( PATH_ROOT .'/controllers/'. ucfirst($file) . '.php') ) {
		include_once( PATH_ROOT .'/controllers/'. ucfirst($file) . '.php' );
	}
}
spl_autoload_register('autoload_controllers');


function autoload_models( $file )
{
	if( file_exists( PATH_ROOT .'/models/'. ucfirst($file) . '.php') ) {
		include_once( PATH_ROOT .'/models/'. ucfirst($file) . '.php' );
	}
}
spl_autoload_register('autoload_models');



function autoload_core( $file )
{
	if( file_exists( PATH_ROOT .'/core/'. strtolower($file) . '.php') ) {
		include_once( PATH_ROOT .'/core/'. strtolower($file) . '.php' );
	}
}
spl_autoload_register('autoload_core');
