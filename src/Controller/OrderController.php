<?php

namespace App\Controller;

use DateTime;
use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * Première étape du tunnel d'achat
     * Choix de l'adresse de livraison et ensuite du transporteur
     */
    #[Route('/commande/livraison', name: 'app_order')]
    public function index(): Response
    {

        $addresses = $this->getUser()->getAddresses();

        if(count($addresses) == 0) {
            return $this->redirectToRoute('app_account_address_form');
        }

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $addresses,
            'action' => $this->generateUrl('app_order_summary')
        ]);


        return $this->render('order/index.html.twig', [
            'deliverForm' => $form->createView(),
        ]);
    }

        /**
     * Deuxième étape du tunnel d'achat
     * Récapitulatif de la commande de l'utilsateur
     * Insertion en BDD
     * Préparation du paiement vers Stripe
     */
    #[Route('/commande/recapitulatif', name: 'app_order_summary')]
    public function add(Request $request, Cart $cart, EntityManagerInterface $entityManager): Response
    {
        if ($request->getMethod() != 'POST') {
            return $this->redirectToRoute('app_cart');
        }

        $products = $cart->getCart();

        $form = $this->createForm(OrderType::class, null, [
            'addresses' => $this->getUser()->getAddresses(),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Stocker les information de commande en BDD

            // Création de la chaîne adresse

            $addressObj = $form->get('addresses')->getData();

            $address = $addressObj->getFirstname().' '.$addressObj->getLastname().'<br/>';
            $address .= $addressObj->getAddress().'<br/>';
            $address .= $addressObj->getPostal().' '.$addressObj->getCity().'<br/>';
            $address .= $addressObj->getCountry().'<br/>';
            $address .= $addressObj->getPhone();

            

            $order = new Order();
            $order->setUser($this->getUser());
            $order->setcreatedAt(new DateTime());
            $order->setState(1);
            $order->setCarrierName($form->get('carriers')->getData()->getName());
            $order->setCarrierPrice($form->get('carriers')->getData()->getPrice());
            $order->setDelivery($address);

            foreach($products as $product) {

            $orderDelail = new OrderDetail();
            $orderDelail->setProductName($product['object']->getName());
            $orderDelail->setProductIllustration($product['object']->getIllustration());
            $orderDelail->setProductPrice($product['object']->getPrice());
            $orderDelail->setProductTva($product['object']->getTva());
            $orderDelail->setProductQuantity($product['qantity']);
            $order->addOrderDetail($orderDelail);

            }

            $entityManager->persist($order);
            $entityManager->flush();
        }
        
        return $this->render('order/summary.html.twig', [
            'choices' => $form->getData(),
            'cart' => $products,
            'totalWt' => $cart->getTotalWt()
        ]);
    }
}
