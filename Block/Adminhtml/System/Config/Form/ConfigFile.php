<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Block\Adminhtml\System\Config\Form;

use Magento\Framework\Data\Form\Element\AbstractElement;

class ConfigFile extends \Magento\Config\Block\System\Config\Form\Field
{

    /**
     * Get element html.
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $additionalCss = '
            <p class="note">
                <span>Please read <a href="https://cloud.google.com/iam/docs/keys-create-delete" target="_blank">'
            . 'Google Documentation</a> on how to get a JSON key file.</span>
            </p>';

        $additionalscript = '
            <script>
            let fieldId = "mftranslation_mftranslationapis_mfgoogleapi_v3_config_file_name";
            let input = document.getElementById(fieldId);
              const myFile = new File(["Config"], "config", {
                    type: "false",
                    lastModified: new Date(),
                });

                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(myFile);
                input.files = dataTransfer.files;

                let inputDelete = document.getElementById(fieldId + "_delete");
                let labelDelete = document.querySelector("label[for=" + fieldId + "_delete]");
                let file = document.querySelector("#row_" + fieldId + " input[type=hidden]");
                let valueDiv = document.querySelector("#row_" + fieldId + " td.value div");

                valueDiv.innerHTML = "";
                valueDiv.innerHTML = inputDelete.outerHTML + labelDelete.outerHTML + file.outerHTML;
            </script>
            ';

        return parent::_getElementHtml($element)
            . $additionalCss
            . ($element->getEscapedValue() ? $additionalscript : '');
    }
}
