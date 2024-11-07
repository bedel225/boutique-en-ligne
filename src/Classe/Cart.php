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
    public function add($product)
    {
        $cart = $this->requestStack->getSession()->get('cart');
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

    public function decrease($id)
    {
        $cart = $this->requestStack->getSession()->get('cart');
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
        $cart = $this->requestStack->getSession()->get('cart');
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
        $cart = $this->requestStack->getSession()->get('cart');
        $priceWt = 0;
        if (!empty($cart)) {
            foreach ($cart as $product) {
                $priceWt += $product['object']->getPricewt() * $product['qty'];
            }
        }

        return $priceWt;
    }
}