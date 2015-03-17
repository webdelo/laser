<?php
namespace core\traits;
trait RequestHandler
{
	private function getRequestHandler()
	{
		return \core\RequestHandler::getInstance();
	}
	
	protected function getPOST()
	{
		return $this->getRequestHandler()->getPOST();
	}
	
	protected function getGET()
	{
		return $this->getRequestHandler()->getGET();
	}
	
	protected function getSESSION()
	{
		return $this->getRequestHandler()->getSESSION();
	}
	
	protected function getSERVER()
	{
		return $this->getRequestHandler()->getSERVER();
	}
	
	protected function getREQUEST()
	{
		return $this->getRequestHandler()->getREQUEST();
	}
	
	protected function getCOOKIE()
	{
		return $this->getRequestHandler()->getCOOKIE();
	}
	
	protected function getCurrentDomain()
	{
		return $this->getRequestHandler()->getCurrentDomain();
	}
	
	protected function getCurrentDomainAlias()
	{
		return $this->getRequestHandler()->getCurrentDomainAlias();
	}
	
	protected function getCurrentLang()
	{
		return $this->getRequestHandler()->getCurrentLang();
	}
	
	protected function getCurrentPart()
	{
		return $this->getRequestHandler()->getCurrentPart();
	}
	
	protected function getMainController()
	{
		return $this->getRequestHandler()->getMainController();
	}
	
	protected function updateGET()
	{
		return $this->getRequestHandler()->updateGET();
	}
	
	protected function updatePOST()
	{
		return $this->getRequestHandler()->updatePOST();
	}
	
	protected function updateREQUEST()
	{
		return $this->getRequestHandler()->updateREQUEST();
	}
	
	protected function updateSERVER()
	{
		return $this->getRequestHandler()->updateSERVER();
	}
	
	protected function updateSESSION()
	{
		return $this->getRequestHandler()->updateSESSION();
	}
	
	protected function updateCOOKIE()
	{
		return $this->getRequestHandler()->updateCOOKIE();
	}
	
	protected function isPart($part)
	{
		return $this->getRequestHandler()->isPart($part);
	}

	protected function isDefaultPart($part)
	{
		return $this->getRequestHandler()->isDefaultPart($part);
	}

	protected function isDefaultLang($lang)
	{
		return $this->getRequestHandler()->isDefaultLang($lang);
	}

	protected function isDefaultController($controller)
	{
		return $this->getRequestHandler()->isDefaultController($controller);
	}

	protected function isCurrentDomainAlias($domainAlias)
	{
		return $this->getRequestHandler()->isCurrentDomainAlias($domainAlias);
	}

	protected function isCurrentDomain($domain)
	{
		return $this->getRequestHandler()->isCurrentDomain($domain);
	}
	
	protected function isMainController($controllerName)
	{
		return $this->getRequestHandler()->isMainController($controllerName);
	}
	
	protected function isIndex()
	{
		return $this->getRequestHandler()->isIndex();
	}
	
	protected function isNotIndex()
	{
		return $this->getRequestHandler()->isNotIndex();
	}
	
	protected function isDeveloperDomain()
	{
		return $this->getRequestHandler()->isDeveloperDomain();
	}
	
	protected function isProductionDomain()
	{
		return $this->getRequestHandler()->isProductionDomain();
	}
	
	public function getCurrentController()
	{
	    return $this->getREQUEST()['controller'];
	}
	
	protected function getDevelopersDomainAlias($domainAlias = null)
	{
		return $this->getRequestHandler()->getDevelopersDomainAlias($domainAlias);
	}
	
	protected function moveRequestLevel()
	{
		return $this->getRequestHandler()->moveRequestLevel();
	}
}