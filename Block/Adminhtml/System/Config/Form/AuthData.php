<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

use Magefan\Translation\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Data\Form\Element\AbstractElement;
use Magento\Framework\Serialize\Serializer\Json;

class AuthData extends \Magento\Config\Block\System\Config\Form\Field
{
    const SECRET_CONTENT_FIELDS = [
        'private_key',
        'private_key_id'
    ];

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Json
     */
    private $json;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Config $config
     * @param Json $json
     * @param array $data
     */
    public function __construct(
        Context              $context,
        ScopeConfigInterface $scopeConfig,
        Config               $config,
        Json                 $json,
        array                $data = []
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->config = $config;
        $this->json = $json;
        parent::__construct($context, $data);
    }

    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $html = '';
        $configOptionHtml = '';
        $scope = $element->getScope();
        $data = $this->config->getEncryptedAuthConfig($scope, (int)$element->getScopeId());

        if ($scope && $data) {

            $i = 0;
            try {
                foreach ($this->json->unserialize($data) as $configTitle => $configValue) {
                    $i++;
                    $configOptionHtml .= '  <tr class="' . ($i%2 ? '_odd-row' : '') . '">
                    <td><div class="data-grid-cell-content">' . $configTitle . '</div></td>
                    <td><div class="data-grid-cell-content">' . (in_array($configTitle, self::SECRET_CONTENT_FIELDS) ? '**************' : $configValue) . '</div></td>
                    </tr>';
                }
            } catch (\Exception $exception) {
                $configOptionHtml = '<span style="color: red">Private Key JSON is not valid. Please upload correct JSON file.<span>';
            }

            if (strlen($configOptionHtml)) {
                $html = '
                <table class="data-grid" style="width:100%">
                      <tr style="text-align: left">
                        <th>Field</th>
                        <th>Value</th>
                      </tr>
                      ' . $configOptionHtml . '
                </table>
                <style>
                    #row_mftranslation_mftranslationapis_mfgoogleapi_v3_auth_data .label label {display:none}
                    #row_mftranslation_mftranslationapis_mfgoogleapi_v3_auth_data .data-grid td,
                    #row_mftranslation_mftranslationapis_mfgoogleapi_v3_auth_data .data-grid th {padding: 1rem;}
                </style>';
            }
        }

        if (!$html) {
            $html = '<style>
                #row_mftranslation_mftranslationapis_mfgoogleapi_v3_auth_data {display:none}
            </style>';
        }

        return $html;
    }
}
