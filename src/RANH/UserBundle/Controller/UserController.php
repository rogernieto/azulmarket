<?php

namespace RANH\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response; 
use RANH\UserBundle\Entity\User;
use RANH\UserBundle\Form\UserType;
use Symfony\Component\HttpFoundation\Request; 
use Symfony\Component\Validator\Constraints as Assert; 
use Symfony\Component\Form\FormError;


class UserController extends Controller
{

    public function homeAction()
    {
        return $this->render('RANHUserBundle:User:home.html.twig');
    }


    public function indexAction(Request $request)
    {   

        $searchQuery = $request->get('query');
        
        if (!empty($searchQuery)) {
            $finder = $this->container->get('fos_elastica.finder.app.user');
            $users = $finder->createPaginatorAdapter($searchQuery);
        }
        else
        {

            $em = $this->getDoctrine()->getManager();        
        //vamos a usar DQL para usar el paginador de KNP
            $dql = "SELECT u FROM RANHUserBundle:User u ORDER BY u.id DESC";
        //ejecutar consulta
            $users= $em->createQuery($dql);
        }



        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $users, $request->query->getInt('page',1),
            6
            );


        $deleteFormAjax = $this->createCustomForm(':USER_ID','DELETE','ranh_user_delete');

        /*
    	$res = 'Lista de Usuarios: <br />';
    	foreach ($users as $user) 
    	{
    		$res .= 'Usuario: '. $user->getUserName() . '<br/>Email: ' . $user->getEmail() . '<br /><br />';
    	}
    	return new Response($res);
		*/
		//return $this->render('RANHUserBundle:User:index.html.twig', array('users' => $users));
        //como todo se esta guardando en la variable pagination entonces eso se va a devolver
        return $this->render('RANHUserBundle:User:index.html.twig', array('pagination' => $pagination,
            'delete_form_ajax'=>$deleteFormAjax->createView()));

    }
    public function registerAction()
    {
        $user = new User();
        $form2 = $this->createCreateAccount($user);

        return $this->render('RANHUserBundle:Security:register.html.twig', array('form2'=>$form2->createView()));
    }

    //Recibimos como parametros la entidad que corresponde
    public function createCreateAccount(User $entity)
    {
        //createForm() Nos sirve para estructurar el formulario 
        $form2 = $this->createForm(new UserType(),$entity,array(
            'action'=>$this->generateUrl('ranh_create_create2'),
            'method'=>'POST'    
            ));
        return $form2;
    }

    public function addAction()
    {
        $user = new User();
        $form =$this->createCreateForm($user);

        return $this->render('RANHUserBundle:User:add.html.twig',array('form'=>$form->createView()));
    }

//Recibimos como parametros la entidad que corresponde
    public function createCreateForm(User $entity)
    {
        //createForm() Nos sirve para estructurar el formulario 
        $form = $this->createForm(new UserType(),$entity,array(
            'action'=>$this->generateUrl('ranh_user_create'),
            'method'=>'POST'    
            ));
        return $form;
    }



    public function create2Action(Request $request)
    {
        $user =  new User(); 
        $form2 = $this->createCreateAccount($user);
        $form2->handleRequest($request);
        if($form2->isValid())
        {


            //definir nuestro constraint
            $passwordConstraint = new Assert\NotBlank();
            //Guardamos en la variable password la data que se ingresa en el campo password
            $password = $form2->get('password')->getData();
            $errorList = $this->get('validator')->validate($password, $passwordConstraint);
            if (count($errorList)== 0 )
            {
            //codificar password
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user,$password);
            //setear password, almacenar el passwword ya encriptado
                $user->setPassword($encoded);
            //iniciamos Doctrine
                $em = $this->getDoctrine()->getManager();
            //indicamos que se hará la persistencia con el objeto user 
                $em->persist($user);
            //se ingresan los datos a BD
                $em->flush();
            //Enviar mensaje de exito 
                $this->addFlash('mensaje','El usuario fue creado exitosamente');
                return $this->redirectToRoute('ranh_user_index');                
            }
            else
            {
                $errorMessage= new FormError($errorList[0]->getMessage());
                $form2->get('password')->addError($errorMessage);
            }

            
        }



        return $this->render('RANHUserBundle:Security:register.html.twig', array('form2' => $form2->createView()));


    }

    public function createAction(Request $request)
    {
        $user = new User();
        //recibimos el objeto user
        $form = $this->createCreateForm($user);
        //Necesitamos obtener el objeto request
        $form->handleRequest($request);
        //Comprobar que la variable form es valida
        if($form->isValid())
        {


            //definir nuestro constraint
            $passwordConstraint = new Assert\NotBlank();
            //Guardamos en la variable password la data que se ingresa en el campo password
            $password = $form->get('password')->getData();
            $errorList = $this->get('validator')->validate($password, $passwordConstraint);
            if (count($errorList)== 0 )
            {
            //codificar password
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user,$password);
            //setear password, almacenar el passwword ya encriptado
                $user->setPassword($encoded);
            //iniciamos Doctrine
                $em = $this->getDoctrine()->getManager();
            //indicamos que se hará la persistencia con el objeto user 
                $em->persist($user);
            //se ingresan los datos a BD
                $em->flush();
            //Enviar mensaje de exito 
                $this->addFlash('mensaje','El usuario fue creado exitosamente');


                return $this->redirectToRoute('ranh_user_index');                
            }
            else
            {
                $errorMessage= new FormError($errorList[0]->getMessage());
                $form->get('password')->addError($errorMessage);
            }

            
        }
        //Renderizar el form si es que existe algun problema 
        return $this->render('RANHUserBundle:User:add.html.twig', array('form' => $form->createView()));

    }



    public function editAction ($id)
    {
        //llamar al get doctrine
        $em = $this->getDoctrine()->getManager();
        //Recuperar el registro del usuario que se quiere editar, recibimos el ID
        $user = $em->getRepository('RANHUserBundle:User')->find($id);

        //validar si existe el usuario 
        if (!$user) 
        {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        //crear un nuevo metodo que se llamara createEditForm y ese sera el formulario, y enviaremos el objeto user
        $form = $this->createEditForm($user);
        //renderizar la vista
        return $this->render('RANHUserBundle:User:edit.html.twig', array('user'=> $user, 'form'=> $form->createView()));


    }

    //crear el metodo de creat edit form y recibir la entidad que sera user
    private function createEditForm(User $entity)
    {
        //crear el formulario y usar el objeto usertype 
        $form = $this->createForm(new UserType(), $entity, array('action'=> $this->generateUrl('ranh_user_update',
            array('id'=>$entity->getId())), 'method'=>'PUT'));

        //retornar formulario creado 
        return $form;     
    }

    //crear la accion updateaction para responder a la renderizacion
    public function updateAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('RANHUserBundle:User')->find($id); 
        //validar si existe el usuario 
        if (!$user) 
        {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        //llamar al metodo para elaborar el formulario 
        $form = $this->createEditForm($user);

        //obtener el form y procesando con request
        $form->handleRequest($request);

        //validar el envio del form
        if($form->isSubmitted() && $form->isValid())
        {
            //recuperar el campo password
            $password = $form->get('password')->getData();

            if (!empty($password)) 
            {
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($user,$password);
                $user->setPassword($encoded);
            }
            else
            {
                $recoverPass = $this->recoverPass($id);

                //setear la misma password
                $user->setPassword($recoverPass[0]['password']);
            }


            //
            if ($form->get('role')->getData() == 'ROLE_ADMIN')
            {
                $user->setIsActive(1);
            }





            $em->flush();

            $successMessage= 'El usuario ha sido modificado';
            $this->addFlash('mensaje',$successMessage);

            //mandar de nuevo a ruta edit con el  parametro del id 
            return $this->redirectToRoute('ranh_user_edit',array('id' => $user->getId()));
        }

        //nuevamente retornar nuestros datos a la vista edit
        return $this->render('RANHUserBundle:User:edit.html.twig', array('user'=> $user, 'form'=>$form->createView()));


    }

//metodo para recuperar contraseña
    public function recoverPass($id)
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT u.password 
            FROM RANHUserBundle:User u
            WHERE u.id = :id'
            )->setParameter('id',$id);
        //setparameter ese metodo dice que el 'id'  va a ser igual al $id 
        $currentPass = $query->getResult();

        return $currentPass;
    }

    public function viewAction($id)
    {
    	$repository = $this->getDoctrine()->getRepository('RANHUserBundle:User'); 
    	$user = $repository->find($id);

        //validar si existe el usuario 
        if (!$user) 
        {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        $deleteForm = $this->createCustomForm($user->getId(), 'DELETE','ranh_user_delete');



        return $this->render('RANHUserBundle:User:view.html.twig',array('user'=>$user,'delete_form'=>$deleteForm->createView()));
    }



/*
    private function createDeleteForm($user)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl('ranh_user_delete',array('id'=>$user)))
        ->setMethod('DELETE')
        ->getForm();
    }
*/

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('RANHUserBundle:User')->find($id);

        //validar si existe el usuario 
        if (!$user) 
        {
            throw $this->createNotFoundException('Usuario no encontrado');
        }

        //$form = $this->createDeleteForm($user);

        //obtenemos el total de ussuarios
        $allUsers = $em->getRepository('RANHUserBundle:User')->findAll();
        $countUsers = count($allUsers);

        //llamar al metodo y enviarle el id recuperado a traves del getId 
        $form =  $this->createCustomForm($user->getId(),'DELETE','ranh_user_delete');
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) 
        {

            //trabajo en ajax
            if ($request->isXMLHttpRequest()) 
            {
                $res = $this->deleteUser($user->getRole(), $em, $user);


                return new Response(
                    json_encode(array('removed'=>$res['removed'], 'message'=> $res['message'], 'countUsers'=>$countUsers  )),
                    200,
                    array('Content-Type'=>'application/json')
                    );
            }

            $res = $this->deleteUser($user->getRole(), $em , $user);

            $this->addFlash($res['alert'],$res['message']);
            return $this->redirectToRoute('ranh_user_index');
        }


    }

    private function deleteUser($role, $em, $user)
    {
        if ($role == 'ROLE_USER') 
        {
            $em->remove($user);
            $em->flush();

            $message= 'El usuario ha sido eliminado';
            $this->addFlash('mensaje',$message);

            $removed = 1;
            //la var alert va a definir el nombre del mensaje  
            $alert = 'mensaje';
        }
        elseif ($role == 'ROLE_ADMIN') 
        {
            $message = 'El usuario no pudo ser eliminado';
            $this->addFlash('mensaje',$message);
            //indica que el usuario no fue eliminado 
            $removed = 0 ; 
            $alert = 'error';

        }

        $arr = array('removed'=> $removed, 'message'=> $message, 'alert'=>$alert);

        return $arr;
    }

    private function createCustomForm($id, $method, $route)
    {
        return $this->createFormBuilder()
        ->setAction($this->generateUrl($route, array('id'=>$id)))
        ->setMethod($method)
        ->getForm();
    }




/*
    public function articlesAction($page)
    {
    	return new Response('Este es mi articulo '.$page) ;

    }
*/
}
