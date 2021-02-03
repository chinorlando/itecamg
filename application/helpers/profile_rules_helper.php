<?php 
function getProfileRules()
{
    return array(
        array(
            'field' => 'username',
            'label' => 'Nombre de Usuario',
            'rules' => 'required|trim',
            'errors' => array(
                'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim',
            'errors' => array(
                'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'old_password',
            'label' => 'Contraseña antigua',
            'rules' => 'required',
            'errors' => array(
                    'required' => 'La %s es obligatoria.'),
        ),
        array(
            'field' => 'password',
            'label' => 'Contraseña',
            'rules' => 'required',
            'errors' => array(
                    'required' => 'La %s es obligatoria.'),
        ),
        array(
            'field' => 'repeat_password',
            'label' => 'Repetir la nueva contraseña',
            'rules' => 'required|trim|matches[password]',
            'errors' => array(
                'required' => 'Debe %s.',
                'matches' => 'La nueva contraseña no coincide'
            ),
        ),
        // array(
        //     'field' => 'repeat_password',
        //     'label' => 'Contraseña',
        //     'rules' => 'trim|matches[password]',
        //     'errors' => array(
        //         'required' => 'Debe coincidir la nueva %s.'),
        // ),
        array(
            'field' => 'nombres',
            'label' => 'Nombres',
            'rules' => 'required',
            'errors' => array(
                    'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'apellido_paterno',
            'label' => 'Apellido Paterno',
            'rules' => 'required|trim',
            'errors' => array(
                'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'apellido_materno',
            'label' => 'Apellido Materno',
            'rules' => 'required|trim',
            'errors' => array(
                    'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'ci',
            'label' => 'CI',
            'rules' => 'required|trim',
            'errors' => array(
                'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'expedido',
            'label' => 'Expedido',
            'rules' => 'required',
            'errors' => array(
                    'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'sexo',
            'label' => 'Sexo',
            'rules' => 'required',
            'errors' => array(
                'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'estado_civil',
            'label' => 'Estado Civil',
            'rules' => 'required',
            'errors' => array(
                    'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'fecha_nacimiento',
            'label' => 'Fecha de Nacimiento',
            'rules' => 'required|trim',
            'errors' => array(
                'required' => 'La %s es obligatorio.'),
        ),
        array(
            'field' => 'direccion',
            'label' => 'Dirección',
            'rules' => 'required|trim',
            'errors' => array(
                    'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'rol',
            'label' => 'Rol',
            'rules' => 'required',
            'errors' => array(
                'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'cargo',
            'label' => 'Cargo',
            'rules' => 'required',
            'errors' => array(
                    'required' => 'El %s es obligatorio.'),
        ),
    );
}
