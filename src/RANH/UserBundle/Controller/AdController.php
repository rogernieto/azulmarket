<?php

namespace RANH\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use RANH\UserBundle\Entity\Ad;
use RANH\UserBundle\Form\AdType;
use Symfony\Component\Validator\Mapping\ElementMetadata;

/**
 * Ad controller.
 *
 */
class AdController extends Controller
{

    /**
     * Lists all Ad entities.
     *
     */
    public function indexAction( $id , Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RANHUserBundle:Ad')->findByUserId($id);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities, $request->query->getInt('pa  ge',1),
            5
        );

        return $this->render('RANHUserBundle:Ad:index.html.twig', array('pagination' => $pagination));
    }
    /**
     * Creates a new Ad entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Ad();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('ad_show', array('id' => $entity->getId())));
        }

        return $this->render('RANHUserBundle:Ad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Ad entity.
     *
     * @param Ad $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Ad $entity)
    {
        $form = $this->createForm(new AdType(), $entity, array(
            'action' => $this->generateUrl('ad_create'),
            'method' => 'POST',
        ));


        return $form;
    }

    /**
     * Displays a form to create a new Ad entity.
     *
     */
    public function newAction($roger)
    {
        $entity = new Ad();
        $form   = $this->createCreateForm($entity);




        $em2 = $this->getDoctrine()->getManager();
        $roger = $em2->getRepository('RANHUserBundle:Article')->find($roger);

        return $this->render('RANHUserBundle:Ad:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'roger'  => $roger,

        ));
    }

    /**
     * Finds and displays a Ad entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RANHUserBundle:Ad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RANHUserBundle:Ad:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Ad entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RANHUserBundle:Ad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ad entity.');
        }

        $editForm = $this->createEditForm($entity);

        return $this->render('RANHUserBundle:Ad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Ad entity.
    *
    * @param Ad $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Ad $entity)
    {
        $form = $this->createForm(new AdType(), $entity, array(
            'action' => $this->generateUrl('ad_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        return $form;
    }
    /**
     * Edits an existing Ad entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RANHUserBundle:Ad')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ad entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('ad', array('id' => $id)));
        }

        return $this->render('RANHUserBundle:Ad:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Ad entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RANHUserBundle:Ad')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ad entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('ad'));
    }

    /**
     * Creates a form to delete a Ad entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ad_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }


    public function obtenerListaAction($id)
    {

        $em = $this->getDoctrine()->getManager();

        $anuncios = $em->getRepository('RANHUserBundle:Article')->subasta($id);
        return $this->render('RANHUserBundle:Ad:lista.html.twig', array(
            'anuncios' => $anuncios

        ));


    }
}
