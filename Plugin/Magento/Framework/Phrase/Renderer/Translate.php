<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Plugin\Magento\Framework\Phrase\Renderer;

use Magento\Framework\TranslateInterface;
use Psr\Log\LoggerInterface;

class Translate
{
    /**
     * @var TranslateInterface
     */
    protected $translator;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @param TranslateInterface $translator
     * @param LoggerInterface $logger
     */
    public function __construct(
        TranslateInterface $translator,
        LoggerInterface $logger
    ) {
        $this->translator = $translator;
        $this->logger = $logger;
    }

    /**
     * @param $subject
     * @param $result
     * @param array $source
     * @param array $arguments
     * @return false|mixed|string
     * @throws \Exception
     */
    public function afterRender($subject, $result, array $source, array $arguments)
    {
        $text = end($source);

        // When translation is not found
        if ($result === $text) {
            /* If phrase contains escaped quotes then use translation for phrase with non-escaped quote */
            $text = strtr($text, ['\"' => '"', "\\'" => "'"]);

            try {
                $data = $this->translator->getData();
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
                throw $e;
            }

            $eols = ["\r\n", "\n\r", "\r", "\n"];
            foreach ($eols as $eol1) {
                foreach ($eols as $eol2) {
                    if ($eol1 === $eol2) {
                        continue;
                    }

                    $text2 = str_replace($eol1, $eol2, $text);
                    if ($text === $text2) {
                        continue;
                    }

                    if (array_key_exists($text2, $data)) {
                        $source[] = $data[$text2];
                        $result = $this->messageFormatter()->render($source, $arguments);
                        break 2;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @return \Magento\Framework\Phrase\Renderer\MessageFormatter|mixed
     */
    private function messageFormatter()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        return $objectManager->get(\Magento\Framework\Phrase\Renderer\MessageFormatter::class);
    }
}
