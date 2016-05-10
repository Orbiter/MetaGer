<?php

namespace App\MetaGer;
use Illuminate\Http\Request;

class Results
{
	private $request;
	private $fokiNames = [];
	private $fokus;
	function __construct (Request $request)
	{
		$this->request = $request;

		$this->fokiNames[] = trans('fokiNames.web');
		$this->fokiNames[] = trans('fokiNames.nachrichten');
		$this->fokiNames[] = trans('fokiNames.wissenschaft');
		$this->fokiNames[] = trans('fokiNames.produktsuche');
		$this->fokiNames[] = trans('fokiNames.bilder');
		$this->fokiNames[] = trans('fokiNames.angepasst');
	}

	public function loadSearchEngines ()
	{
		# Wenn keine persönlichen Einstellungen gesetzt sind, dafür aber ein existierender Fokus von uns,
		# werden die zu dem Fokus gehörenden Suchmaschinen angeschaltet.
		return $this->request->input('focus', 'test');
		return var_dump($this->fokiNames);
	}

}