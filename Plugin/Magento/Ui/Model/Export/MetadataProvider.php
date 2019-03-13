<?php


namespace Magefan\Translation\Plugin\Magento\Ui\Model\Export;

class MetadataProvider
{

    public function afterGetHeaders(
        \Magento\Ui\Model\Export\MetadataProvider $subject,
        $result
    ) {
        $id = trim(str_replace('"', '', $result[0]));
        if ('ID' == $id && 'Original Text' == trim($result[1]) && 'Translate Text' ==  trim($result[3])) {
            $result = [
                'key_id',
                'string',
                'store_id',
                'translate',
                'locale'];
        }
        return $result;
    }
}
