<?php

namespace RANH\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request; 

class SecurityController extends Controller
{


	public function loginAction()
	{
		//llamar al servicio de autenticacion
		$authenticationUtils = $this->get('security.authentication_utils');
		//var error para que manejar el metodo de errores
		$error = $authenticationUtils->getLastAuthenticationError();

		//si se autentificado el usuario de manera erronea, esta var recuperara el user
		$lastUsername = $authenticationUtils->getLastUsername();

		//devolver la vista de login 
		return $this->render('RANHUserBundle:Security:login.html.twig', array('last_username'=>$lastUsername, 'error'=>$error));
	}


	public function loginCheckAction()
	{

	}
}
