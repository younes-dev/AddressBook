<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DeleteController extends Controller
{
    /**
     * @Route("/delete/{id}", name="deleteAddress")
     */
    public function indexAction(Request $request,$id){
        $address = $this->getDoctrine()
            ->getRepository(Address::class)
            ->find($id);

        if (!$address) {
            $this->addFlash('error', 'Address not found!');
        }else{
            $this->getDoctrine()->getManager()
                ->remove($address);
            $this->getDoctrine()->getManager()
                ->flush();
            $this->addFlash('success', 'Address deleted successfully!');
        }

        return $this->redirectToRoute('homepage');
    }
}