<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart 
{
    public function __construct(private RequestStack $requestStack)
    {
        
    }

    
    /**
     * add()
     * Méthode pour ajouter un produit au panier
     */
    public function add($product)
    {

        $cart = $this->requestStack->getSession()->get('cart');

        if (isset($cart[$product->getId()])) {

            $cart[$product->getId()] = [
                'object' => $product,
                'qantity' => $cart[$product->getId()]['qantity'] +1
            ];
        } else {

            $cart[$product->getId()] = [
                'object' => $product,
                'qantity' => 1
            ];
        }

        $this->requestStack->getSession()->set('cart', $cart);

        
    }

    /**
     * decrease()
     * Méthode pour supprimer la quantité un produit du panier
     */

    public function decrease($id) 
    {
        $cart = $this->requestStack->getSession()->get('cart'); 

        if ($cart[$id]['qantity'] > 1) {
            $cart[$id]['qantity'] = $cart[$id]['qantity'] - 1;
        } else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    /**
     * totalQuantity()
     * Méthode pour retourner le nombre total de produit dans le panier
     */

    public function totalQuantity() 
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $quantity = 0;
        foreach($cart as $product) {
    
            $quantity += $product['qantity'];
        }
        return $quantity; 
    
    }

    /**
     * getTotalWt()
     * Méthode pour retourner prix total ttc du ou des produits dans le panier
     */

    public function getTotalWt() 
    {
        $cart = $this->requestStack->getSession()->get('cart', []);
        $price = 0;
        foreach($cart as $product) {
    
            $price += ($product['object']->getPriceWt() * $product['qantity']);
        }
        return $price;
    }

    /**
     * remove()
     * Méthode pour supprimer la totalité du panier
     */

    public function remove() 
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    /**
     * getCart()
     * Méthode retournant le panier
     */

    public function getCart() 
    {
        return $this->requestStack->getSession()->get('cart',);
    }
}