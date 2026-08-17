import http from 'k6/http';
import { check } from 'k6';

const BASE_URL = 'http://127.0.0.1:8000';

// =====================================================
// KONFIGURASI UJIAN
// =====================================================

const UJIAN_ID = 22;

// Token ujian ID 22
const TOKEN_UJIAN = 'WVQVXT';

const PASSWORD = '12345678';


// =====================================================
// LOAD TEST
// =====================================================

export const options = {

    scenarios: {

        ujian_100_siswa: {

            executor: 'shared-iterations',

            // 100 siswa aktif bersamaan
            vus: 100,

            // 1 siswa = 1 iterasi
            iterations: 100,

            // Waktu maksimal test
            maxDuration: '3m',

        },

    },

};


// =====================================================
// FUNCTION AMBIL CSRF
// =====================================================

function getCsrf(body) {

    const match = body.match(
        /name=["']_token["'][^>]*value=["']([^"']+)["']/
    );

    return match
        ? match[1]
        : null;

}


// =====================================================
// MAIN
// =====================================================

export default function () {

    // =================================================
    // AKUN SISWA
    // =================================================

    const username =
        'loadtest' +
        String(__VU).padStart(3, '0');


    console.log(
        `VU ${__VU}: mulai test menggunakan ${username}`
    );


    // =================================================
    // 1. BUKA LOGIN
    // =================================================

    const loginPage = http.get(
        `${BASE_URL}/login`
    );


    const csrf = getCsrf(
        loginPage.body
    );


    check(loginPage, {

        'login page 200': (r) =>
            r.status === 200,

        'CSRF ditemukan': () =>
            csrf !== null,

    });


    if (!csrf) {

        console.log(
            `VU ${__VU}: CSRF LOGIN tidak ditemukan`
        );

        return;

    }


    // =================================================
    // 2. LOGIN
    // =================================================

    const loginResponse = http.post(

        `${BASE_URL}/login`,

        {

            _token: csrf,

            username: username,

            password: PASSWORD,

            remember: '0',

        },

        {

            redirects: false,

        }

    );


    const loginLocation =
        loginResponse.headers['Location'] || '';


    const loginBerhasil =
        loginResponse.status === 302 &&
        !loginLocation.endsWith('/login');


    check(loginResponse, {

        'login berhasil': () =>
            loginBerhasil,

    });


    if (!loginBerhasil) {

        console.log(
            `VU ${__VU}: LOGIN GAGAL`
        );

        console.log(
            `VU ${__VU}: status ${loginResponse.status}`
        );

        console.log(
            `VU ${__VU}: location ${loginLocation}`
        );

        return;

    }


    // =================================================
    // 3. BUKA HALAMAN UJIAN
    // =================================================

    const ujianPage = http.get(

        `${BASE_URL}/cbt/ujian-saya`

    );


    check(ujianPage, {

        'halaman ujian siswa 200': (r) =>
            r.status === 200,

    });


    if (ujianPage.status !== 200) {

        console.log(
            `VU ${__VU}: halaman ujian gagal`
        );

        return;

    }


    // =================================================
    // 4. BUKA HALAMAN MULAI
    // =================================================
    //
    // Kita coba buka halaman mulai.
    //
    // Karena halaman mulai membutuhkan
    // session cbt_access_{ujian_id},
    // token harus diverifikasi terlebih dahulu.
    // =================================================

    const tokenResponse = http.post(

        `${BASE_URL}/cbt/ujian/${UJIAN_ID}/token`,

        {

            _token: getCsrf(ujianPage.body),

            token: TOKEN_UJIAN,

        },

        {

            redirects: false,

        }

    );


    const tokenLocation =
        tokenResponse.headers['Location'] || '';


    const tokenBerhasil =
        tokenResponse.status === 302 &&
        !tokenLocation.includes('/login');


    check(tokenResponse, {

        'token ujian berhasil': () =>
            tokenBerhasil,

    });


    if (!tokenBerhasil) {

        console.log(
            `VU ${__VU}: TOKEN GAGAL`
        );

        console.log(
            `VU ${__VU}: status ${tokenResponse.status}`
        );

        console.log(
            `VU ${__VU}: location ${tokenLocation}`
        );

        console.log(
            `VU ${__VU}: response ${tokenResponse.body}`
        );

        return;

    }


    // =================================================
    // 5. BUKA HALAMAN MULAI
    // =================================================

    const mulaiPage = http.get(

        `${BASE_URL}/cbt/ujian/${UJIAN_ID}/mulai`

    );


    check(mulaiPage, {

        'halaman mulai ujian 200': (r) =>
            r.status === 200,

    });


    if (mulaiPage.status !== 200) {

        console.log(
            `VU ${__VU}: halaman mulai gagal - ${mulaiPage.status}`
        );

        return;

    }


    // =================================================
    // 6. AMBIL CSRF
    // =================================================

    const csrfUjian =
        getCsrf(mulaiPage.body) || csrf;


    // =================================================
    // 7. BUAT PENGERJAAN SISWA
    // =================================================
    //
    // INI PENTING.
    //
    // Setiap siswa akan membuat pengerjaan
    // masing-masing.
    //
    // Tidak menggunakan pengerjaan_id 19
    // secara hard-code.
    // =================================================

    const pengerjaanResponse = http.post(

        `${BASE_URL}/cbt/ujian/${UJIAN_ID}/pengerjaan`,

        {

            _token: csrfUjian,

        },

        {

            redirects: false,

        }

    );


    const pengerjaanLocation =
        pengerjaanResponse.headers['Location'] || '';


    const pengerjaanBerhasil =
        pengerjaanResponse.status === 302 &&
        pengerjaanLocation !== '';


    check(pengerjaanResponse, {

        'pengerjaan berhasil dibuat': () =>
            pengerjaanBerhasil,

    });


    if (!pengerjaanBerhasil) {

        console.log(
            `VU ${__VU}: GAGAL MEMBUAT PENGERJAAN`
        );

        console.log(
            `VU ${__VU}: status ${pengerjaanResponse.status}`
        );

        console.log(
            `VU ${__VU}: response ${pengerjaanResponse.body}`
        );

        return;

    }


    // =================================================
    // 8. AMBIL ID PENGERJAAN
    // =================================================
    //
    // Contoh Location:
    //
    // /cbt/pengerjaan/123
    //
    // Kita mengambil angka 123 tersebut.
    // =================================================

    const pengerjaanMatch =
        pengerjaanLocation.match(
            /\/cbt\/pengerjaan\/(\d+)/
        );


    if (!pengerjaanMatch) {

        console.log(
            `VU ${__VU}: ID PENGERJAAN TIDAK DITEMUKAN`
        );

        console.log(
            `VU ${__VU}: location = ${pengerjaanLocation}`
        );

        return;

    }


    const pengerjaanId =
        pengerjaanMatch[1];


    console.log(
        `VU ${__VU}: ${username} -> pengerjaan ${pengerjaanId}`
    );


    // =================================================
    // 9. BUKA HALAMAN PENGERJAAN
    // =================================================

    const pengerjaanPage = http.get(

        `${BASE_URL}/cbt/pengerjaan/${pengerjaanId}`

    );


    check(pengerjaanPage, {

        'halaman pengerjaan 200': (r) =>
            r.status === 200,

    });


    if (pengerjaanPage.status !== 200) {

        console.log(
            `VU ${__VU}: halaman pengerjaan gagal`
        );

        return;

    }


    // =================================================
    // 10. CSRF TERBARU
    // =================================================

    const csrfJawaban =
        getCsrf(pengerjaanPage.body) || csrfUjian;


    // =================================================
    // 11. KIRIM JAWABAN
    // =================================================
    //
    // SETIAP SISWA menggunakan pengerjaanId
    // miliknya sendiri.
    // =================================================

    const jawabanResponse = http.post(

        `${BASE_URL}/cbt/pengerjaan/${pengerjaanId}/jawaban`,

        {

            _token: csrfJawaban,

            soal_id: '21',

            jawaban: 'A',

        },

        {

            headers: {

                Accept: 'application/json',

                'X-Requested-With':
                    'XMLHttpRequest',

                Referer:
                    `${BASE_URL}/cbt/pengerjaan/${pengerjaanId}`,

            },

        }

    );


    const jawabanBerhasil =
        jawabanResponse.status === 200 &&
        jawabanResponse.body.includes(
            '"success":true'
        );


    check(jawabanResponse, {

        'jawaban berhasil': () =>
            jawabanBerhasil,

    });


    if (!jawabanBerhasil) {

        console.log(
            `VU ${__VU}: JAWABAN GAGAL`
        );

        console.log(
            `VU ${__VU}: status ${jawabanResponse.status}`
        );

        console.log(
            `VU ${__VU}: ${jawabanResponse.body}`
        );

        return;

    }


    console.log(
        `VU ${__VU}: ${username} BERHASIL`
    );

}