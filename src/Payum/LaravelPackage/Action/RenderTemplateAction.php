<?php
namespace Payum\LaravelPackage\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\RenderTemplate;

class RenderTemplateAction implements ActionInterface {

	/**
	 * {@inheritDoc}
	 */
	public function execute($request)
	{
		/** @var $request RenderTemplate */
		RequestNotSupportedException::assertSupports($this, $request);

		$request->setResult(view($request->getTemplateName(), $request->getParameters())->render());
	}

	/**
	 * {@inheritDoc}
	 */
	public function supports($request)
	{
		return $request instanceof RenderTemplate;
	}
}