<?php

namespace RANH\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use RANH\UserBundle\Entity\State;
use RANH\UserBundle\Form\StateType;

/**
 * State controller.
 *
 */
class StateController extends Controller
{

    /**
     * Lists all State entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('RANHUserBundle:State')->findAll();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $entities, $request->query->getInt('page',1),
            5
        );

        return $this->render('RANHUserBundle:State:index.html.twig', array('pagination' => $pagination));


    }
    /**
     * Creates a new State entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new State();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('state_show', array('id' => $entity->getId())));
        }

        return $this->render('RANHUserBundle:State:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a State entity.
     *
     * @param State $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(State $entity)
    {
        $form = $this->createForm(new StateType(), $entity, array(
            'action' => $this->generateUrl('state_create'),
            'method' => 'POST',
        ));

        return $form;
    }

    /**
     * Displays a form to create a new State entity.
     *
     */
    public function newAction()
    {
        $entity = new State();
        $form   = $this->createCreateForm($entity);

        return $this->render('RANHUserBundle:State:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a State entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RANHUserBundle:State')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find State entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RANHUserBundle:State:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing State entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RANHUserBundle:State')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find State entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('RANHUserBundle:State:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a State entity.
    *
    * @param State $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(State $entity)
    {
        $form = $this->createForm(new StateType(), $entity, array(
            'action' => $this->generateUrl('state_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));


        return $form;
    }
    /**
     * Edits an existing State entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('RANHUserBundle:State')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find State entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('state', array('id' => $id)));
        }

        return $this->render('RANHUserBundle:State:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a State entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('RANHUserBundle:State')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find State entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('state'));
    }

    /**
     * Creates a form to delete a State entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('state_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-sm btn-danger', 'style'=>'width: 100%')))
            ->getForm()
        ;
    }
}
