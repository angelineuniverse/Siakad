<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Aktivasi Akun Baru</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Rubik:wght@300;400;500;600;700&display=swap" 
        rel="stylesheet">
</head>
<body>
    <style>
        *{
            margin: 0;
            padding: 0;
            font-family: 'Inter';
        }
        p{
            font-size: 12px;
        }
        main{
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            min-height: 100vh;
            background-color: rgb(229 231 235);
        }
        #main-box{
            padding: 2rem;
            width: 100%;
            border-radius: 0.375rem;
            background: white;
            filter: filter: drop-shadow(0 25px 25px rgb(0 0 0 / 0.15));
        }
        #main-box #btn{
            width: 150px;
            padding: 15px 0;
            background-color: rgb(236 72 153);
            color: white;
            font-weight: 700;
            border: 0;
            text-align: center;
            cursor: pointer;
            border-radius: 0.375rem;
            margin-top: 30px;
        }
    </style>
    <main>
        <div id="main-box">
            <div style="display: flex; justify-content:center; margin-bottom: 40px;">
                {{-- <img style="width: 90px;" src="" alt="logo"> --}}
                <h1>DISINI LOGO</h1>
            </div>
            <h2 style="margin-bottom: 1rem; font-size: 16px;">Hai {{$name}}</h2>
            <p style="margin-bottom: 20px;">Selamat datang di Angeline SIAKAD! 
                Kami sangat senang menyambutmu sebagai anggota baru di platform kami.</p>
            <p style="text-align: justify; margin-bottom: 40px;">Angeline SIAKAD 
                adalah sebuah platform untuk mengelola kebutuhan universitas dalam menjalankan seluruh management baik itu dosen, mahasiswa, keuangan dan masih banyak lainnya.</p>
            <p>Untuk mengaktifkan akunmu, silakan klik tombol di bawah ini :</p>
            <div style="display: flex; justify-content:center;">
                <a href={{$urls}} style="text-decoration-line: none;" id="btn">Aktivasi Akun</a>
            </div>
            <p style="margin: 30px 0;">Setelah mengklik tautan tersebut, akunmu akan segera aktif dan kamu bisa mulai 
                melakukan kegiatan managerial di Angeline SIAKAD.</p>
            <p>Salam, <br> <b>Angeline SIAKAD</b></p>
            <div style="width: 100%; display: flex; justify-content:center; margin-top:50px; column-gap: 1rem;">
                <a href="https://icons8.com/icon/123922/tiktok">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 30 30">
                        <path d="M24,4H6C4.895,4,4,4.895,4,6v18c0,1.105,0.895,2,2,2h18c1.105,0,2-0.895,2-2V6C26,4.895,25.104,4,24,4z M22.689,13.474 c-0.13,0.012-0.261,0.02-0.393,0.02c-1.495,0-2.809-0.768-3.574-1.931c0,3.049,0,6.519,0,6.577c0,2.685-2.177,4.861-4.861,4.861 C11.177,23,9,20.823,9,18.139c0-2.685,2.177-4.861,4.861-4.861c0.102,0,0.201,0.009,0.3,0.015v2.396c-0.1-0.012-0.197-0.03-0.3-0.03 c-1.37,0-2.481,1.111-2.481,2.481s1.11,2.481,2.481,2.481c1.371,0,2.581-1.08,2.581-2.45c0-0.055,0.024-11.17,0.024-11.17h2.289 c0.215,2.047,1.868,3.663,3.934,3.811V13.474z"></path>
                    </svg>
                </a>
                <a href="">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 48 48">
                        <path fill="#3F51B5" d="M42,37c0,2.762-2.238,5-5,5H11c-2.761,0-5-2.238-5-5V11c0-2.762,2.239-5,5-5h26c2.762,0,5,2.238,5,5V37z"></path><path fill="#FFF" d="M34.368,25H31v13h-5V25h-3v-4h3v-2.41c0.002-3.508,1.459-5.59,5.592-5.59H35v4h-2.287C31.104,17,31,17.6,31,18.723V21h4L34.368,25z"></path>
                    </svg>
                </a>
                <a href="">
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="30" height="30" viewBox="0 0 48 48">
                        <radialGradient id="yOrnnhliCrdS2gy~4tD8ma_Xy10Jcu1L2Su_gr1" cx="19.38" cy="42.035" r="44.899" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fd5"></stop><stop offset=".328" stop-color="#ff543f"></stop><stop offset=".348" stop-color="#fc5245"></stop><stop offset=".504" stop-color="#e64771"></stop><stop offset=".643" stop-color="#d53e91"></stop><stop offset=".761" stop-color="#cc39a4"></stop><stop offset=".841" stop-color="#c837ab"></stop></radialGradient><path fill="url(#yOrnnhliCrdS2gy~4tD8ma_Xy10Jcu1L2Su_gr1)" d="M34.017,41.99l-20,0.019c-4.4,0.004-8.003-3.592-8.008-7.992l-0.019-20	c-0.004-4.4,3.592-8.003,7.992-8.008l20-0.019c4.4-0.004,8.003,3.592,8.008,7.992l0.019,20	C42.014,38.383,38.417,41.986,34.017,41.99z"></path><radialGradient id="yOrnnhliCrdS2gy~4tD8mb_Xy10Jcu1L2Su_gr2" cx="11.786" cy="5.54" r="29.813" gradientTransform="matrix(1 0 0 .6663 0 1.849)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#4168c9"></stop><stop offset=".999" stop-color="#4168c9" stop-opacity="0"></stop></radialGradient><path fill="url(#yOrnnhliCrdS2gy~4tD8mb_Xy10Jcu1L2Su_gr2)" d="M34.017,41.99l-20,0.019c-4.4,0.004-8.003-3.592-8.008-7.992l-0.019-20	c-0.004-4.4,3.592-8.003,7.992-8.008l20-0.019c4.4-0.004,8.003,3.592,8.008,7.992l0.019,20	C42.014,38.383,38.417,41.986,34.017,41.99z"></path><path fill="#fff" d="M24,31c-3.859,0-7-3.14-7-7s3.141-7,7-7s7,3.14,7,7S27.859,31,24,31z M24,19c-2.757,0-5,2.243-5,5	s2.243,5,5,5s5-2.243,5-5S26.757,19,24,19z"></path><circle cx="31.5" cy="16.5" r="1.5" fill="#fff"></circle><path fill="#fff" d="M30,37H18c-3.859,0-7-3.14-7-7V18c0-3.86,3.141-7,7-7h12c3.859,0,7,3.14,7,7v12	C37,33.86,33.859,37,30,37z M18,13c-2.757,0-5,2.243-5,5v12c0,2.757,2.243,5,5,5h12c2.757,0,5-2.243,5-5V18c0-2.757-2.243-5-5-5H18z"></path>
                    </svg>
                </a>
            </div>
            <p style="text-align: center; margin-top: 10px; font-size: 10px;">Powered by Angeline Universe</p>
        </div>
    </main>
</body>
</html>