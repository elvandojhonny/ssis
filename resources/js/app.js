import './bootstrap';

import '@tabler/core/dist/js/tabler.min.js';

import { Html5Qrcode } from 'html5-qrcode';

window.Html5Qrcode = Html5Qrcode;

document.addEventListener('DOMContentLoaded', function () {

    const sidebar =
        document.querySelector('.navbar-vertical');

    const toggle =
        document.getElementById('ssisMenuToggle');

    const overlay =
        document.getElementById('sidebar-overlay');


    if (!sidebar || !toggle || !overlay) {
        return;
    }


    function bukaSidebar() {

        sidebar.classList.add(
            'ssis-sidebar-open'
        );

        overlay.classList.add(
            'active'
        );

        document.body.style.overflow =
            'hidden';
    }


    function tutupSidebar() {

        sidebar.classList.remove(
            'ssis-sidebar-open'
        );

        overlay.classList.remove(
            'active'
        );

        document.body.style.overflow =
            '';
    }


    toggle.addEventListener(
        'click',
        bukaSidebar
    );


    overlay.addEventListener(
        'click',
        tutupSidebar
    );


    /*
     * Tutup sidebar setelah memilih menu
     * pada perangkat mobile.
     */

    sidebar
        .querySelectorAll('.nav-link:not(.disabled)')
        .forEach(function (link) {

            link.addEventListener(
                'click',
                function () {

                    if (
                        window.innerWidth < 992
                    ) {

                        tutupSidebar();

                    }

                }
            );

        });


    /*
     * Jika layar kembali ke desktop,
     * bersihkan status mobile.
     */

    window.addEventListener(
        'resize',
        function () {

            if (
                window.innerWidth >= 992
            ) {

                tutupSidebar();

            }

        }
    );

});