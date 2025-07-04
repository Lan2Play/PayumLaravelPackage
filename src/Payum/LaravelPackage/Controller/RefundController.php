<?php
namespace Payum\LaravelPackage\Controller;

use Payum\Core\Reply\ReplyInterface;
use Payum\Core\Request\Refund;
use Symfony\Component\HttpFoundation\Request;

class RefundController extends PayumController
{
    public function doAction($payum_token)
    {
        /** @var Request $request */
        $request = app('request');
        $request->attributes->set('payum_token', $payum_token);

        $token = $this->getPayum()->getHttpRequestVerifier()->verify($request);

        $gateway = $this->getPayum()->getGateway($token->getGatewayName());

        try {
            $gateway->execute(new Refund($token));
        } catch (ReplyInterface $reply) {
            return $this->convertReply($reply);
        }

        $this->getPayum()->getHttpRequestVerifier()->invalidate($token);

        if($token->getAfterUrl()){
            return redirect($token->getAfterUrl());
        }

        return response(null, 204);
    }
}