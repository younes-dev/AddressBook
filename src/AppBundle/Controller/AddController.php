<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Form\AddressType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AddController extends Controller
{
    /**
     * @Route("/add", name="addAddress")
     */
    public function indexAction(Request $request)
    {
        $form = $this->createForm(AddressType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ) {
            $em = $this->getDoctrine()->getManager();
            /** @var Address $newAddress */
            $newAddress = $form->getData();

            $file = $newAddress->getPicture();
            if($file){
                $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('upload_pic_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochure' property to store the PDF file name
                // instead of its contents
                $newAddress->setPicture($fileName);
            }

            $em->persist($newAddress);
            $em->flush();

            $this->addFlash('success', 'Address added successfully!');
            return $this->redirectToRoute('homepage');

        }elseif($form->isSubmitted() && $form->isValid() === false){

            $this->addFlash('error', 'Please enter valid data');

        }
        return $this->render('default/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
