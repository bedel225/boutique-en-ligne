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
}