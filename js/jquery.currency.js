/*
 * jQuery Currency v0.6 ( January 2015 )
 * Simple, unobtrusive currency converting and formatting
 *
 * Copyright 2015, sMarty
 * Free to use and abuse under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * http://coderspress.com
*
 */

(function ($) {

    $.fn.currency = function (method) {

        var methods = {

            init: function (options) {
                var settings = $.extend({}, this.currency.defaults, options);
                return this.each(function () {
                    var $element = $(this),
                        element = this;
                    var value = 0;

                    if ($element.is(':input')) {
                        value = $element.val();
                    } else {
                        value = $element.text();
                    }

                    if (helpers.isNumber(value)) {
                        if (settings.convertFrom != '') {

                            if ($element.is(':input')) {
                                $element.val(value + ' ' + settings.convertLoading);
                            } else {
                                $element.html(value + ' ' + settings.convertLoading);
                            }

                            $.post(settings.convertLocation, {
                                amount: value,
                                from: settings.convertFrom,
                                to: settings.region
                            }, function (data) {
                                value = data;

                                if ($element.is(':input')) {
                                    $element.val(helpers.format_currency(value, settings));
                                } else {
                                    $element.html(helpers.format_currency(value, settings));
                                }
                            });
                        } else {

                            if ($element.is(':input')) {
                                $element.val(helpers.format_currency(value, settings));
                            } else {
                                $element.html(helpers.format_currency(value, settings));
                            }

                        }
                    }
                });
            },
        }

        var helpers = {
            format_currency: function (amount, settings) {
                var bc = settings.region;
                var currency_before = '';
                var currency_after = '';
                if (bc == 'ALL') currency_before = '<span class="currency">Lek<\/span>';
                if (bc == 'ARS') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'AWG') currency_before = '<span class="currency">f<\/span>';
                if (bc == 'AUD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'BSD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'BBD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'BYR') currency_before = '<span class="currency">p.<\/span>';
                if (bc == 'BZD') currency_before = '<span class="currency">BZ$<\/span>';
                if (bc == 'BMD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'BOB') currency_before = '<span class="currency">$b<\/span>';
                if (bc == 'BAM') currency_before = '<span class="currency">KM<\/span>';
                if (bc == 'BWP') currency_before = '<span class="currency">P<\/span>';
                if (bc == 'BRL') currency_before = '<span class="currency">R$<\/span>';
                if (bc == 'BND') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'CAD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'KYD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'CLP') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'CNY') currency_before = '<span class="currency">&yen;<\/span>';
                if (bc == 'COP') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'CRC') currency_before = '<span class="currency">c<\/span>';
                if (bc == 'HRK') currency_before = '<span class="currency">kn<\/span>';
                if (bc == 'CZK') currency_before = '<span class="currency">Kc<\/span>';
                if (bc == 'DKK') currency_before = '<span class="currency">kr<\/span>';
                if (bc == 'DOP') currency_before = '<span class="currency">RD$<\/span>';
                if (bc == 'XCD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'EGP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'SVC') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'EEK') currency_before = '<span class="currency">kr<\/span>';
                if (bc == 'EUR') currency_before = '<span class="currency">&euro;<\/span>';
                if (bc == 'FKP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'FJD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'GBP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'GHC') currency_before = '<span class="currency">c<\/span>';
                if (bc == 'GIP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'GTQ') currency_before = '<span class="currency">Q<\/span>';
                if (bc == 'GGP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'GYD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'HNL') currency_before = '<span class="currency">L<\/span>';
                if (bc == 'HKD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'HUF') currency_before = '<span class="currency">Ft<\/span>';
                if (bc == 'ISK') currency_before = '<span class="currency">kr<\/span>';
                if (bc == 'IDR') currency_before = '<span class="currency">Rp<\/span>';
                if (bc == 'IND') currency_before = '<span class="currency">&#8377;<\/span>';
                if (bc == 'IMP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'JMD') currency_before = '<span class="currency">J$<\/span>';
                if (bc == 'JPY') currency_before = '<span class="currency">&yen;<\/span>';
                if (bc == 'JEP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'LVL') currency_before = '<span class="currency">Ls<\/span>';
                if (bc == 'LBP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'LRD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'LTL') currency_before = '<span class="currency">Lt<\/span>';
                if (bc == 'MYR') currency_before = '<span class="currency">RM<\/span>';
                if (bc == 'MXN') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'MZN') currency_before = '<span class="currency">MT<\/span>';
                if (bc == 'NAD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'ANG') currency_before = '<span class="currency">f<\/span>';
                if (bc == 'NZD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'NIO') currency_before = '<span class="currency">C$<\/span>';
                if (bc == 'NOK') currency_before = '<span class="currency">kr<\/span>';
                if (bc == 'PAB') currency_before = '<span class="currency">B/.<\/span>';
                if (bc == 'PYG') currency_before = '<span class="currency">Gs<\/span>';
                if (bc == 'PEN') currency_before = '<span class="currency">S/.<\/span>';
                if (bc == 'PLN') currency_before = '<span class="currency">zl<\/span>';
                if (bc == 'RON') currency_before = '<span class="currency">lei<\/span>';
                if (bc == 'SHP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'SGD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'SBD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'SOS') currency_before = '<span class="currency">S<\/span>';
                if (bc == 'ZAR') currency_before = '<span class="currency">R<\/span>';
                if (bc == 'SEK') currency_before = '<span class="currency">kr<\/span>';
                if (bc == 'CHF') currency_before = '<span class="currency">CHF<\/span>';
                if (bc == 'SRD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'SYP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'TWD') currency_before = '<span class="currency">NT$<\/span>';
                if (bc == 'TTD') currency_before = '<span class="currency">TT$<\/span>';
                if (bc == 'TRY') currency_before = '<span class="currency">TL<\/span>';
                if (bc == 'TRL') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'TVD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'GBP') currency_before = '<span class="currency">&pound;<\/span>';
                if (bc == 'USD') currency_before = '<span class="currency">$<\/span>';
                if (bc == 'UYU') currency_before = '<span class="currency">$U<\/span>';
                if (bc == 'VEF') currency_before = '<span class="currency">Bs<\/span>';
                if (bc == 'ZWD') currency_before = '<span class="currency">Z$<\/span>';
                if (currency_before == '' && currency_after == '') currency_before = '$';
                var output = '';

                if (!settings.hidePrefix) output += currency_before;
                output += helpers.number_format(amount, settings.decimals, settings.decimal, settings.thousands);

                if (!settings.hidePostfix) output += currency_after;
                return output;
            },

            // Kindly borrowed from http://phpjs.org/functions/number_format
            number_format: function (number, decimals, dec_point, thousands_sep) {
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                    s = '',
                    toFixedFix = function (n, prec) {
                        var k = Math.pow(10, prec);
                        return '' + Math.round(n * k) / k;
                    };

                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');

                if (s[0].length > 3) {
                    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
                }

                if ((s[1] || '').length < prec) {
                    s[1] = s[1] || '';
                    s[1] += new Array(prec - s[1].length + 1).join('0');
                }
                return s.join(dec);
            },
            isNumber: function (n) {
                return !isNaN(parseFloat(n)) && isFinite(n);
            }
        }

        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method "' + method + '" does not exist in currency plugin!');
        }
    }

    $.fn.currency.defaults = {
        region: "USD", // The 3 digit ISO code you want to display your currency in
        thousands: ",", // Thousands separator
        decimal: ".", // Decimal separator
        decimals: 2, // How many decimals to show
        hidePrefix: false, // Hide any prefix
        hidePostfix: false, // Hide any postfix
        convertFrom: "", // If converting, the 3 digit ISO code you want to convert from,
        convertLoading: "(Converting...)", // Loading message appended to values while converting
        convertLocation: "convert.php" // Location of convert.php file
    }

    $.fn.currency.settings = {}

})(jQuery);