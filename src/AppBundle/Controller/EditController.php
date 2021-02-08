<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Address;
use AppBundle\Form\AddressType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EditController extends Controller
{
    /**
     * @Route("/edit/{id}", name="editAddress")
     */
    public function indexAction(Request $request, $id){
        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->find($id);
        if (!$address) {
            $this->addFlash('error', 'Address not found!');
            return $this->redirectToRoute('homepage');
        }

        try{
            $picture = new File($this->getParameter('upload_pic_directory').$address->getPicture());
            if (!$picture){
                $picture = null;
            }
            $address->setPicture($picture);
        }catch (\Exception $exception){

        }

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            /** @var Address $newAddress */
            $newAddress = $form->getData();

            $file = $newAddress->getPicture();
            if($file){
                $fileName = md5(uniqid()).'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('upload_pic_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $newAddress->setPicture($fileName);
            }

            $this->addFlash('success', 'Address Edited successfully!');
            $em->persist($newAddress);
            $em->flush();
            return $this->redirectToRoute('homepage');

        }elseif($form->isSubmitted() && $form->isValid() === false){

            $this->addFlash('error', 'Please enter valid data');

        }
        return $this->render('default/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

}