<?php

class UsuariosController extends Controller
{

    public function __construct() {
        parent::__construct();
        $this->model = new Usuarios();
    }

    
    /**
     * Muestra el Listado
     *
     * @return void
     */
    public function index()
    {
        $title = 'Usuarios';        
        $usuarios = $this->model->all();
        $this->view('usuarios', compact('title','usuarios'));
    }


    /**
     * Buscar registro
     *
     * @return void
     */
    public function find( $id = NULL)
    {
        $usuarios = $this->model->where( ['id'=>$id] )->results();

        //si no devuelve resultados se envia un 404
        if( is_array($usuarios) and count($usuarios) ){
            $this->response(200, $usuarios[0] );
        } else {
            $this->response(404, []);
        }        
    }


    /**
     * UGuarda un registro
     *
     * @return void
     */
    public function store()
    {
        $id = $this->getPost('id');
        $nombres = $this->getPost('nombres');
        $apellidos = $this->getPost('apellidos');
        $email = $this->getPost('email');
        $nacimiento = $this->getPost('nacimiento');
        $edad = $this->getPost('edad');
        $cedula = $this->getPost('cedula');

        
        if( $cedula == '' ){
            $this->response(400, ['field'=>'cedula', 'text'=>'Cédula'] );
        }

        if( $email == '' ){
            $this->response(400, ['field'=>'email', 'text'=>'Correo electrónico'] );
        }

        if( $nombres == '' ){
            $this->response(400, ['field'=>'cedula', 'text'=>'Cédula'] );
        }


        $this->model->cedula        = $this->cSQL($cedula);
        $this->model->nombres       = $this->cSQL($nombres);
        $this->model->apellidos     = $this->cSQL($apellidos);
        $this->model->email         = $this->cSQL($email);
        $this->model->nacimiento    = $this->cSQL($nacimiento);
        $this->model->edad          = $this->cSQL($edad);        

        $id = $this->model->insert();
        $this->response(201, [
            'id' => $id,
            'cedula' => $cedula,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'email' => $email,
            'nacimiento' => $nacimiento,
            'edad' => $edad,
        ]);

    }

    
    /**
     * UGuarda un registro
     *
     * @return void
     */
    public function update()
    {
        $id = $this->getPut('id');
        $nombres = $this->getPut('nombres');
        $apellidos = $this->getPut('apellidos');
        $email = $this->getPut('email');
        $nacimiento = $this->getPut('nacimiento');
        $edad = $this->getPut('edad');
        $cedula = $this->getPut('cedula');

        if(  $cedula == '' ){
            $this->response(400, ['field'=>'cedula', 'text'=>'Cédula'] );
        }

        if(  $email == '' ){
            $this->response(400, ['field'=>'email', 'text'=>'Correo electrónico'] );
        }

        if(  $nombres == '' ){
            $this->response(400, ['field'=>'cedula', 'text'=>'Cédula'] );
        }

        $this->model->cedula        = $this->cSQL($cedula);
        $this->model->nombres       = $this->cSQL($nombres);
        $this->model->apellidos     = $this->cSQL($apellidos);
        $this->model->email         = $this->cSQL($email);
        $this->model->nacimiento    = $this->cSQL($nacimiento);
        $this->model->edad          = $this->cSQL($edad);
        

        $this->model->update($id);
        $this->response(201, [
            'id' => $id,
            'cedula' => $cedula,
            'nombres' => $nombres,
            'apellidos' => $apellidos,
            'email' => $email,
            'nacimiento' => $nacimiento,
            'edad' => $edad,
        ]);
    }

    
    /**
     * UGuarda un registro
     *
     * @return void
     */
    public function destroy( $id = NULL )
    {
        $id = $this->cSQL($id);
        $this->model->destroy($id);
        $this->response(201, []);
    }

}
