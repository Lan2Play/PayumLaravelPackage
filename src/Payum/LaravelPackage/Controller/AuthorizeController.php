<?php
namespace Payum\LaravelPackage\Controller;

use Payum\Core\Reply\ReplyInterface;
use Payum\Core\Request\Authorize;
use Symfony\Component\HttpFoundation\Request;

class AuthorizeController extends PayumController
{
    public function doAction($payum_token)
    {
        /** @var Request $request */
        $request = app('request');
        $request->attributes->set('payum_token', $payum_token);

        $token = $this->getPayum()->getHttpRequestVerifier()->verify($request);

        $gateway = $this->getPayum()->getGateway($token->getGatewayName());

        try {
            $gateway->execute(new Authorize($token));
        } catch (ReplyInterface $reply) {
            return $this->convertReply($reply);
        }

        $this->getPayum()->getHttpRequestVerifier()->invalidate($token);

        return redirect($token->getAfterUrl());
    }
}