<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

declare(strict_types=1);

namespace Magefan\Translation\Rewrite\Magento\Framework\Phrase\Renderer;

use Magento\Framework\TranslateInterface;
use Psr\Log\LoggerInterface;
use Magento\Framework\Phrase\Renderer\MessageFormatter;

class Translate extends \Magento\Framework\Phrase\Renderer\Translate
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
     * @var MessageFormatter
     */
    private $messageFormatter;

    /**
     * Renderer construct
     *
     * @param TranslateInterface $translator
     * @param LoggerInterface $logger
     * @param MessageFormatter $messageFormatter
     */
    public function __construct(
        TranslateInterface $translator,
        LoggerInterface $logger,
        MessageFormatter $messageFormatter
    ) {
        $this->translator = $translator;
        $this->logger = $logger;
        $this->messageFormatter = $messageFormatter;
    }

    /**
     * Render source text
     *
     * @param array $source
     * @param array $arguments
     * @return string
     * @throws \Exception
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function render(array $source, array $arguments)
    {
        $text = end($source);
        /* If phrase contains escaped quotes then use translation for phrase with non-escaped quote */
        $text = strtr($text, ['\"' => '"', "\\'" => "'"]);

        try {
            $data = $this->translator->getData();
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            throw $e;
        }

        if (array_key_exists($text, $data)) {
            $source[] = $data[$text];
        } else {

            $eols = ["\r\n", "\n\r", "\r", "\n"];
            $found = false;
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
                        $found = true;
                        break 2;
                    }

                }
            }

            if (!$found) {
                $source[] = end($source);
            }
        }

        //$source[] = array_key_exists($text, $data) ? $data[$text] : end($source);

        return $this->messageFormatter->render($source, $arguments);
    }

}
