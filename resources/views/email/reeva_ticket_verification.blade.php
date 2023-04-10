<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Email Verification</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        /**
         * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
         */
        @media screen {
            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 400;
                src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 700;
                src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
            }
        }

        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%; /* 1 */
            -webkit-text-size-adjust: 100%; /* 2 */
        }

        table,
        td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
        }

        img {
            -ms-interpolation-mode: bicubic;
        }

        a[x-apple-data-detectors] {
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
        }

        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }

        body {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }


        table {
            border-collapse: collapse !important;
        }

        a {
            color: #1a82e2;
        }

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>

</head>
<body style="background-color: #e9ecef;">

<table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" valign="top" style="padding: 36px 24px;">
                        {{--              <a href="#" target="_blank" style="display: inline-block;">--}}
                        {{--              <img src="{{asset('amsw-files/AMSW-2021-logo.png')}}" alt="Logo" border="0" width="48" style="display: block; width: 100px;">--}}
                        {{--              </a>--}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="left" bgcolor="#ffffff"
                        style="padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                        <h1 style="text-align: center;margin: 0; font-size: 32px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">
                            Tiket Schematics Reeva Anda Telah Terverifikasi</h1>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="left" bgcolor="#ffffff"
                        style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                        {{-- <p style="margin: 0;">Hi {{$name}}</p> --}}
                        <p> 
                            Halo, {{ $nama }}!
                            Terima kasih telah memesan tiket Schematis Reeva 2022
                            Proses pemesanan dan pembayaran tiket Anda telah berhasil. 
                        </p>
                        <table>
                            <tr>
                              <td>Nama</td>
                              <td>: {{ $nama }}</td>
                            </tr>
                            <tr>
                              <td>Email</td>
                              <td>: {{ $email }}</td>
                            </tr>
                            <tr>
                              <td>NIK</td>
                              <td>: {{ $nik }}</td>
                            </tr>
                            <tr>
                              <td>Jumlah Tiket</td>
                              <td>: {{ $jumlah_tiket }}</td>
                            </tr>
                            <tr>
                              <td>Biaya</td>
                              <td>: Rp {{ $biaya }},00</td>
                            </tr>
                            <tr>
                              <td>Status</td>
                              <td>: {{ ucfirst(trans($status)) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="left" bgcolor="#ffffff"
                        style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                        <p style="margin: 0;" >Untuk informasi mengenai alur penukaran tiket, peraturan dan tata tertib selama acara berlangsung dapat diakses pada website schematics.its.ac.id
                            <br>Jika terdapat kendala atau pertanyaan, silakan hubungi kami pada :<br>
                            <table>
                                <tr>
                                    <td>Instagram</td>
                                    <td>: schematics.its</td>
                                </tr>
                                <tr>
                                    <td>Twitter</td>
                                    <td>: {{'@'}}schematics_its</td>
                                </tr>
                                <tr>
                                    <td>Tiktok</td>
                                    <td>: schematics.its</td>
                                </tr>
                            </table>
                            </p>
                            <br>
                        <p style="margin: 0;">
                            <a style="text-align: center; padding: 36px 24px 0;" href="{{$url.'/dashboard'}}">
                                Menuju Dashboard Schematics
                            </a>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td align="left" bgcolor="#ffffff"
                        style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align="center" bgcolor="#e9ecef" style="padding: 24px;">
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" bgcolor="#e9ecef"
                        style="text-align: center; padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
                        <p style="text-align: center; margin: 0;">©Schematics 2022</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
