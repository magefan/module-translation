<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Glory to Ukraine! Glory to the heroes!
 */

namespace Magefan\Translation\Model\Translation\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Status implements OptionSourceInterface
{
    /**
     * @var \Magefan\Translation\Model\Translation
     */
    protected $translation;

    /**
     * Status constructor.
     * @param \Magefan\Translation\Model\Translation $translation
     */
    public function __construct(\Magefan\Translation\Model\Translation $translation)
    {
        $this->translation = $translation;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->translation->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
