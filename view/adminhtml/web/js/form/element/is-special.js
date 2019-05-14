/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

define([
    'jquery',
    'Magento_Ui/js/form/element/select'
], function ($, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            customName: '${ $.parentName }.${ $.index }_input'
        },
        selectOption: function (id) {
            if ($("#"+id).val()==0) {
                $('div[data-index="mf_locale"]').show();
            } else {
               $('div[data-index="mf_locale"]').hide();
            }
        },
    });
});

