<?php
/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

namespace Magefan\Translation\Controller\Adminhtml\Translation;

class Delete extends \Magento\Backend\App\Action
{

    /**
     * Authorization level of a basic admin session
     */
    const ADMIN_RESOURCE = 'Magefan_Translation::addedit';

    /**
     * Delete action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('key_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($id) {
            $title = "";
            try {
                $model = $this->_objectManager->create(\Magefan\Translation\Model\Translation::class);
                $model->load($id);
                $title = $model->getTitle();
                $model->delete();
                $this->messageManager->addSuccess(__('The translation has been deleted.'));
                $this->_eventManager->dispatch(
                    'adminhtml_translation_on_delete',
                    ['title' => $title, 'status' => 'success']
                );
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_eventManager->dispatch(
                    'adminhtml_translation_on_delete',
                    ['title' => $title, 'status' => 'fail']
                );
                $this->messageManager->addError($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['key_id' => $id]);
            }
        }
        $this->messageManager->addError(__('We can\'t find a translation to delete.'));
        return $resultRedirect->setPath('*/*/');
    }
}
