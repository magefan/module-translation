<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Api;

/**
 * @api
 * Interface TranslationRepositoryInterface
 */
interface TranslationRepositoryInterface
{
    /**
     * @param int $id
     * @return \Magefan\Translation\Api\Data\TranslationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * @param Data\TranslationInterface $translation
     * @return \Magefan\Translation\Api\Data\TranslationInterface
     */
    public function save(\Magefan\Translation\Api\Data\TranslationInterface $translation);

    /**
     * @param Data\TranslationInterface $translation
     * @return bool Will returned True if deleted
     */
    public function delete(\Magefan\Translation\Api\Data\TranslationInterface $translation);

    /**
     * Remove item by id.
     *
     * @api
     * @param int $id
     * @return bool Will returned True if deleted
     */
    public function deleteById($id);

    /**
     * Returns some translation by id
     *
     * @param int $id Translation name.
     * @return \Magefan\Translation\Api\Data\TranslationInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($id);

    /**
     * Create new item.
     *
     * @api
     * @param string $data
     * @return string
     */
    public function create($data);

    /**
     * Update  using data
     *
     * @param int $id
     * @param string $data
     * @return string || false
     */
    public function update($id, $data);
}
