/**
 * ladtopics cleaners removes course elements that should not be available for authenticated users that did NOT agree to a ceratin policy.
 *
 * @module     format/ladtopics
 * @class      LADTopics
 * @copyright  2019 Niels Seidel <niels.seidel@fernuni-hagen.de>
 * @license    MIT
 * @since      3.1
 */
define(['jquery'], function ($) {
    
    return {
        init: function () {
            console.log('Blocked Version');
            // hide unused div
            let box = $("#region-main-box");
            const h = box.outerHeight();
            box.change(function () {
                if (box.outerHeight() > h) {
                    box.show();
                }
            });
            box.hide();

            // Zeige die Werbebox, die per Default ausgeblendet ist
            $(document).ready(function () {
                $('.policy-withdraw-box').show();
                $('.policy-withdraw-box').css('diaplay','inline');
            });

            let blackpages = '';
            if (M.cfg.wwwroot.includes('127.0.0.1') || M.cfg.wwwroot.includes('localhost')) {

            } else if (M.cfg.wwwroot == 'https://aple.fernuni-hagen.de') {
                // add 1801 page modules
                blackpages += 'module-248,module-281,module-318,module-343';
                // add page modules from "Module 1D"
                blackpages += ',module-374,module-375,module-378,module-381,module-384,module-392,module-395,module-398,module-401,module-404,module-407,module-414,module-417,module-420,module-423,module-424,module-425,module-426,module-427';
            }
            blackpages = blackpages.split(',');

            $('.page').each(function (i, val) {
                if (blackpages.indexOf($(this).attr('id')) !== -1) {
                    $(this).hide();
                }
            });

            $('.activity.usenet').hide();

            console.log("done");
            const link = `${M.cfg.wwwroot}/course/format/ladtopics/policy.php`;
            $('div.ladtopics').prepend(
                ` 
                <div class="alert alert-primary my-0" style="cursor:pointer;">
                    <div class="w-75">
                        Da Sie nicht allen Richtlinien zugestimmt haben, erhalten Sie nur eine eingeschränkte Lernunterstützung.<br />
                        Im Folgenden können Sie den Richtlinien nachträglich zustimmen, sofern Sie wollen.<br />
                        <button type="button" class="btn btn-primary mt-2" onclick="javascript:window.location.href='${link}'">Übersicht der Kursrichtlinien</button>
                    </div>
                </div>
                `
            );
        }
    };
});