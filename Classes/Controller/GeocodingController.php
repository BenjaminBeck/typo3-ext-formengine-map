<?php

namespace CedricZiel\FormEngine\Map\Controller;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Http\AjaxRequestHandler;
use TYPO3\CMS\Core\Http\Response;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility;
use TYPO3\CMS\Core\Http\JsonResponse;

class GeocodingController
{
    const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json?';

    /**
     * @param mixed              $ajaxParameters
     * @param AjaxRequestHandler $ajaxRequestHandler
     */
    public function geocode( $ajaxParameters, Response $Response)
    {
//        /** @var ServerRequest $request */
//        $request = $ajaxParameters['request'];
		$address = $_GET["query"];
        # $address = $request->getQueryParams()['query'];
        $queryData = http_build_query(
            [
                'key'     => $this->getApiKey(),
                'address' => $address,
                'language' => $this->getApiLanguage(), 
            ]
        );
        $report = [];
        $url = static::API_URL.$queryData;
        $result = GeneralUtility::getUrl($url, 0, false, $report);
		return (new JsonResponse())->setPayload((array)json_decode($result));
		// $ajaxRequestHandler->setContentFormat('application/json');
		// $ajaxRequestHandler->setContent(['data' => $result]);
    }

    /**
     * Retreives the API key from the extension configuration.
     *
     * @return string
     */
    protected function getApiKey()
    {
		$backendConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
			->get('formengine_map');
//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($backendConfiguration, __FILE__.':L'.__LINE__);
//        /** @var ConfigurationUtility $configurationUtility */
//        $configurationUtility = static::getObjectManager()->get(ConfigurationUtility::class);
//        $extensionConfiguration = $configurationUtility->getCurrentConfiguration('formengine_map');
		return $backendConfiguration['googleMapsGeocodingApiKey'];
//        /** @var ConfigurationUtility $configurationUtility */
//        $configurationUtility = $this->getObjectManager()->get(ConfigurationUtility::class);
//        $extensionConfiguration = $configurationUtility->getCurrentConfiguration('formengine_map');
//        return $extensionConfiguration['googleMapsGeocodingApiKey']['value'];
    }

    /**
     * Retreives the API language setting
     *
     * @return string
     */
    protected function getApiLanguage()
    {
        /** @var ConfigurationUtility $configurationUtility */
        $configurationUtility = $this->getObjectManager()->get(ConfigurationUtility::class);
        $extensionConfiguration = $configurationUtility->getCurrentConfiguration('formengine_map');
        return $extensionConfiguration['googleMapsGeocodingApiLanguage']['value'];
    }

    /**
     * @return ObjectManagerInterface
     */
    protected function getObjectManager() {
        return GeneralUtility::makeInstance(ObjectManager::class);
    }
}
