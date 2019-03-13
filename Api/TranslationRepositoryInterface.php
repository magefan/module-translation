<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Api;

/**
 * Interface TranslationRepositoryInterface
 * @package Magefan\Translation\Api
 */
interface TranslationRepositoryInterface
{

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id);

    /**
     * @param Data\TranslationInterface $translation
     * @return mixed
     */
    public function save(\Magefan\Translation\Api\Data\TranslationInterface $translation);



    /**
     * @param Data\TranslationInterface $translation
     * @return mixed
     */
    public function delete(\Magefan\Translation\Api\Data\TranslationInterface $translation);


    /**
     * Remove item by id.
     *
     * @api
     * @param int $id.
     * @return bool.
     */
    public function deleteById($id);




    /**
     * Returns some translation by id
     *
     * @api
     * @param int $id Translation name.
     * @return object Translation
     */
    public function get($id);

    /**
     * Create new item.
     *
     * @api
     * @param string $data.
     * @return string.
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
