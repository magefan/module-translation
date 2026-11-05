/**
 * Copyright © Magefan (support@magefan.com). All rights reserved.
 * Please visit Magefan.com for license details (https://magefan.com/end-user-license-agreement).
 */

define([
    'jquery',
    'Magento_Ui/js/form/components/fieldset',
    'Magefan_Translation/js/mf-upgrade-plan-popup'
], function ($, Fieldset, mfPopup) {
    'use strict';

    return Fieldset.extend({
        initialize: function () {
            this._super();
            this._addOverlay();

            return this;
        },

        _addOverlay: function () {
            var self = this;
            var checkInterval = setInterval(function () {
                /* The same fieldset may exist in several forms on one page (e.g. Content Staging modal) */
                var $fieldset = $('[data-index="' + self.index + '"] .admin__fieldset')
                    .not('.mf-locked-fieldset')
                    .first();

                if (!$fieldset.length) {
                    return;
                }

                clearInterval(checkInterval);
                $fieldset.addClass('mf-locked-fieldset').css('position', 'relative');
                $('<div>').css({
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    right: 0,
                    bottom: 0,
                    zIndex: 10,
                    cursor: 'pointer'
                }).on('click', function () {
                    mfPopup('Extra', 'auto-translate', 'fieldset');
                }).appendTo($fieldset);
            }, 500);
        }
    });
});
