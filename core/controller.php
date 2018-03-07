<?php

class Controller
{
    
    public function __construct() {
        header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
    }

    /**
	* Si no existe la variable POST se devuelve una cadena vacía ''
	* @param undefined $clave
	* 
	* @return
	*/
	protected function getPost($clave)
	{
		if(isset($_POST[$clave]) && ! empty($_POST[$clave]))
		{
            return $_POST[$clave];
        }
        return '';
    }


    protected function getPut($clave)
	{
        parse_str(file_get_contents('php://input'), $put_vars);
		if(isset($put_vars[$clave]) && ! empty($put_vars[$clave]))
		{
            return $put_vars[$clave];
        }
        return '';
    }


    /**
     * Undocumented function
     *
     * @param [type] $view
     * @return void
     */
	protected function view($view, $params = [])
	{
        extract($params);
		include PATH_ROOT . 'views/' .$view. '.php';
    }


    protected function cSQL( $value, $default = NULL )
	{
		if( $value === NULL OR $value === '' )
		{
			return $default;
		}

    	$value = trim($value); 
		$value = stripslashes($value); 
        $value = filter_var($value, FILTER_SANITIZE_STRING);
        return $value;      
    }



    /**
     * Undocumented function
     *
     * @param [type] $status
     * @param [type] $statusText
     * @param [type] $response
     * @return void
     */
    protected function response($status, $response)
    {
     
        $response = [
            'status'        => $status,
            'response'      => $response
        ];
        
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($response, JSON_PRETTY_PRINT);
        exit;
    }


}