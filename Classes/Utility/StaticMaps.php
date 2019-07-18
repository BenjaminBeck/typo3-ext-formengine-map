<?php

namespace CedricZiel\FormEngine\Map\Utility;

use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Object\ObjectManager;
use TYPO3\CMS\Extbase\Object\ObjectManagerInterface;
use TYPO3\CMS\Extbase\Reflection\ObjectAccess;
use TYPO3\CMS\Extensionmanager\Utility\ConfigurationUtility;

class StaticMaps
{
    const GOOGLE_STATICMAP_URL = 'https://maps.googleapis.com/maps/api/staticmap?';

    /**
     * Computes a static maps url.
     *
     * @param array $currentValue
     *
     * @return string
     */
    public static function getStaticMapsUrl($currentValue = null)
    {
        $formattedAddress = ObjectAccess::getPropertyPath($currentValue, 'formatted_address');

        if ($formattedAddress === null) {
            return '';
        }

        $parameters = http_build_query(
            [
                'key'     => static::getApiKey(),
                'size'    => '1000x200',
                'zoom'    => 14,
                'center'  => $formattedAddress,
                'markers' => $formattedAddress,
            ]
        );

        return static::GOOGLE_STATICMAP_URL.$parameters;
    }

    /**
     * Retreives the API key from the extension configuration.
     *
     * @return string
     */
    public static function getApiKey()
    {

		$backendConfiguration = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ExtensionConfiguration::class)
			->get('formengine_map');
//		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($backendConfiguration, __FILE__.':L'.__LINE__);

//        /** @var ConfigurationUtility $configurationUtility */
//        $configurationUtility = static::getObjectManager()->get(ConfigurationUtility::class);
//        $extensionConfiguration = $configurationUtility->getCurrentConfiguration('formengine_map');
//
        return $backendConfiguration['googleMapsGeocodingApiKey'];
    }

    /**
     * @return ObjectManagerInterface
     */
    protected static function getObjectManager()
    {
        return \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(ObjectManager::class);
    }
}
