<?php 
function getLoginRules()
{
	return array(
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'required|trim',
            'errors' => array(
            	'required' => 'El %s es obligatorio.'),
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required|trim',
            'errors' => array(
                    'required' => 'El %s es obligatorio.'),
        )
	);
}
