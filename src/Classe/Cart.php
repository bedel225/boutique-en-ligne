<?php

namespace App\Classe;

use Symfony\Component\HttpFoundation\RequestStack;

class Cart
{
    public function __construct(
        private RequestStack $requestStack,
    ) {
        // $this->session = $requestStack->getSession();
    }
    public function getCart()
    {
        return $this->requestStack->getSession()->get('cart');
    }


    /*
     * add()
     * fonction permetant l'ajout d'un produit
     */
    public function add($product)
    {
        $cart = $this->getCart();
        if (isset($cart[$product->getId()])) {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => $cart[$product->getId()]['qty'] +1,
            ];
        }else {
            $cart[$product->getId()] = [
                'object' => $product,
                'qty' => 1,
            ];
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }


    /*
    * decrease()
    * fonction permetant la suppresion d'un produit
    */
    public function decrease($id)
    {
        $cart = $this->getCart();
        if (isset($cart[$id]) && $cart[$id]['qty']>1) {
            $cart[$id]['qty'] = $cart[$id]['qty'] -1;
        }else {
            unset($cart[$id]);
        }

        $this->requestStack->getSession()->set('cart', $cart);
    }

    public function remove()
    {
        return $this->requestStack->getSession()->remove('cart');
    }

    public function fullQuantity()
    {
        $cart = $this->getCart();
        $quantity = 0;
        if (!empty($cart)) {
            foreach ($cart as $product) {
                $quantity += $product['qty'];
            }
        }

        return $quantity;
    }

    public function getTotalWt()
    {
        $cart = $this->getCart();
        $priceWt = 0;
        if (!empty($cart)) {
            foreach ($cart as $product) {
                $priceWt += $product['object']->getPricewt() * $product['qty'];
            }
        }

        return $priceWt;
    }
}