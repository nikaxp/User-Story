<?php

namespace UserStoryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UserStoryBundle\Entity\Address;
use UserStoryBundle\Entity\Person;
use UserStoryBundle\Form\PersonType;
use UserStoryBundle\Form\AddressType;
use Symfony\Component\HttpFoundation\File;
use Symfony\Component\Form\Form;

class PersonController extends Controller
{
    /**
     * @Route("/new", name = "createPerson")
     * @Method("GET")
     */
    public function newFormAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person, array(
            'action' => $this->generateUrl('newPerson')
        ));
        $form->handleRequest($request);

        return $this->render('UserStoryBundle:Person:new.html.twig', array(
            'form' => $form->createView()
        ));
    }
    /**
     * @Route("/new", name = "newPerson")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $person = $form->getData();
            $this->fileBrochure($form, $person);

            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('showPerson', array(
                'id' => $person->getId()
            ));
        }

        return new Response("Incorrect data. Try create Person again.");


    }


    /**
     * @Route("/{id}/modify", name = "modifyFormPerson")
     * @Method("GET")
     */
    public function modifyFormAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getRepository('UserStoryBundle:Person');
        $person = $repo->find($id);

        if($person) {
            $address = new Address();
            $form = $this->createForm(PersonType::class, $person, array(
                'action' => $this->generateUrl('modifyPerson', array('id' => $id))
            ));

            $form->handleRequest($request);

            $addressForm = $this->createForm(AddressType::class, $address, array(
                'action' => $this->generateUrl('addAddress', array('id' => $id))
            ));

            return $this->render('UserStoryBundle:Person:modify.html.twig', array(
                'form' => $form->createView(), 'person' =>$person,
                'addressForm' => $addressForm->createView()
            ));
        }
            return new Response("No person found");
    }


    /**
     * @Route("/{id}/modify", name = "modifyPerson")
     * @Method("POST")
     */
    public function modifyAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getRepository('UserStoryBundle:Person');
        $person = $repo->find($id);

        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $person = $form->getData();
            $this->fileBrochure($form, $person);

            $em = $this->getDoctrine()->getManager();
            $em->persist($person);
            $em->flush();
            return $this->redirectToRoute('showPerson', array(
                'id'=>$person->getId()
            ));
        }
        return new Response("Incorrect data. Try modify Person again.");


    }



    /**
     * @Route("/", name = "showAll")
     */
    public function showAllAction()
    {
        $repo = $this->getDoctrine()->getRepository('UserStoryBundle:Person');
        $people = $repo->findAll();

        return $this->render('UserStoryBundle:Person:showAll.html.twig', array(
            'people' => $people
        ));
    }

    /**
     * @Route("/{id}", name = "showPerson")
     */

    public function showPersonAction($id)
    {
        $repo = $this->getDoctrine()->getRepository('UserStoryBundle:Person');
        $person = $repo->find($id);
//        $address = $person->getAddresses();
//        var_dump($address);

        if($person) {
            return $this->render('UserStoryBundle:Person:showPerson.html.twig', array(
                'person' => $person,
//                'address' => $address,
            ));
        }
        return new Response("Person not found");

    }

    /**
     * @Route("/{id}/delete", name = "deletePerson")
     */
    public function deleteAction($id)
    {
        $repo = $this->getDoctrine()->getRepository('UserStoryBundle:Person');
        $person = $repo->find($id);
        if($person) {
            $alias = $person->getAlias();
            $em = $this->getDoctrine()->getManager();
            $em->remove($person);
            $em->flush();
            return $this->render('UserStoryBundle:Person:delete.html.twig', array(
                'alias' => $alias
            ));
        }
        return new Response("Person not found");

    }


    /**
     * @Route("/{id}/addAddress", name = "addAddress")
//     * @Method("POST")
     */
    public function addAddressAction(Request $request, $id)
    {
        $repo = $this->getDoctrine()->getRepository('UserStoryBundle:Person');
        $person = $repo->find($id);

        $address = new Address();
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $address = $form->getData();
            $address->setPerson($person);
            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();
            return $this->redirectToRoute('showPerson', array(
                'id' => $address->getPerson()->getId()
            ));
        }

        return new Response("Incorrect data. Try create Person again.");

    }




public function fileBrochure(Form $form, Person $person )
{
    /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file */
    $file = $person->getBrochure();
    if ($file) {
        // Generate a unique name for the file before saving it
        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        // Move the file to the directory where brochures are stored
        $brochuresDir = $this->container->getParameter('kernel.root_dir') . '/../web/uploads/brochures';
        $file->move($brochuresDir, $fileName);

        // Update the 'brochure' property to store the PDF file name
        // instead of its contents
        $person->setBrochure($fileName);
    }
    return $person;
}

}
