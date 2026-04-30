/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */
define(['Magento_Ui/js/modal/alert', 'mage/translate'], function (mageAlert, $t) {
    'use strict';

    var DEFAULT_PLAN = 'Plus';
    var DEFAULT_UTM_MEDIUM = 'edit';
    var DEFAULT_UTM_CAMPAIGN = 'upgrade-popup';
    var BASE_URL = 'https://magefan.com/magento-2-translation-extension/pricing?utm_source=admin';

    /**
     * @param {string} [plan]        - Plan name displayed in the message (e.g. 'Plus', 'Extra')
     * @param {string} [utmMedium]   - utm_medium value for the pricing URL (e.g. 'edit', 'config')
     * @param {string} [utmCampaign] - utm_campaign value for the pricing URL (e.g. 'upgrade-popup')
     */
    return function (plan, utmMedium, utmCampaign) {
        plan = plan || DEFAULT_PLAN;
        var url = BASE_URL
            + '&utm_medium=' + (utmMedium || DEFAULT_UTM_MEDIUM)
            + '&utm_campaign=' + (utmCampaign || DEFAULT_UTM_CAMPAIGN);
        var content = $t('This option is available in Magefan Translation <strong>%1</strong> plan only.').replace('%1', plan);
        mageAlert({
            title: $t('You cannot use this option.'),
            content: content,
            buttons: [{
                text: $t('Upgrade Plan Now'),
                class: 'action primary accept',
                click: function () {
                    window.open(url);
                    this.closeModal(true);
                }
            }]
        });
    };
});
