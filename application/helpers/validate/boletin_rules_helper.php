<?php 
function getBoletinRules()
{
    return array(
        array(
            'field' => 'ci',
            'label' => 'Carnet de Identidad',
            'rules' => 'required|trim',
            'errors' => array(
                'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'dob',
            'label' => 'Fecha de nacimiento',
            'rules' => 'required|trim',
            'errors' => array(
                    'required' => 'La %s es obligatoria.'),
        )
    );
}