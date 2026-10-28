define([
    'Magento_Catalog/js/components/import-handler'
], function (ImportHandler) {
    'use strict';

    var transliterationMap = {
        'ä': 'ae',
        'ö': 'oe',
        'ü': 'ue',
        'ß': 'ss',
        'ł': 'l'
    };

    return ImportHandler.extend({
        updateValue: function () {
            this._super();

            if (!this.allowImport) {
                return;
            }

            this.value(this.slugify(this.value()));
        },

        slugify: function (text) {
            return String(text || '')
                .toLowerCase()
                .replace(/[äöüßł]/g, function (character) {
                    return transliterationMap[character];
                })
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
    });
});
