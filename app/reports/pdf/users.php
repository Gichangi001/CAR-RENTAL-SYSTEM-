<?php
/*
 *   Crafted On Thu Mar 02 2023
 *   Author Martin (martin@devlan.co.ke)
 * 
 *   www.devlan.co.ke
 *   hello@devlan.co.ke
 *
 *
 *   The Devlan Solutions LTD End User License Agreement
 *   Copyright (c) 2022 Devlan Solutions LTD
 *
 *
 *   1. GRANT OF LICENSE 
 *   Devlan Solutions LTD hereby grants to you (an individual) the revocable, personal, non-exclusive, and nontransferable right to
 *   install and activate this system on two separated computers solely for your personal and non-commercial use,
 *   unless you have purchased a commercial license from Devlan Solutions LTD. Sharing this Software with other individuals, 
 *   or allowing other individuals to view the contents of this Software, is in violation of this license.
 *   You may not make the Software available on a network, or in any way provide the Software to multiple users
 *   unless you have first purchased at least a multi-user license from Devlan Solutions LTD.
 *
 *   2. COPYRIGHT 
 *   The Software is owned by Devlan Solutions LTD and protected by copyright law and international copyright treaties. 
 *   You may not remove or conceal any proprietary notices, labels or marks from the Software.
 *
 *
 *   3. RESTRICTIONS ON USE
 *   You may not, and you may not permit others to
 *   (a) reverse engineer, decompile, decode, decrypt, disassemble, or in any way derive source code from, the Software;
 *   (b) modify, distribute, or create derivative works of the Software;
 *   (c) copy (other than one back-up copy), distribute, publicly display, transmit, sell, rent, lease or 
 *   otherwise exploit the Software. 
 *
 *
 *   4. TERM
 *   This License is effective until terminated. 
 *   You may terminate it at any time by destroying the Software, together with all copies thereof.
 *   This License will also terminate if you fail to comply with any term or condition of this Agreement.
 *   Upon such termination, you agree to destroy the Software, together with all copies thereof.
 *
 *
 *   5. NO OTHER WARRANTIES. 
 *   DEVLAN SOLUTIONS LTD  DOES NOT WARRANT THAT THE SOFTWARE IS ERROR FREE. 
 *   DEVLAN SOLUTIONS LTD SOFTWARE DISCLAIMS ALL OTHER WARRANTIES WITH RESPECT TO THE SOFTWARE, 
 *   EITHER EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO IMPLIED WARRANTIES OF MERCHANTABILITY, 
 *   FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT OF THIRD PARTY RIGHTS. 
 *   SOME JURISDICTIONS DO NOT ALLOW THE EXCLUSION OF IMPLIED WARRANTIES OR LIMITATIONS
 *   ON HOW LONG AN IMPLIED WARRANTY MAY LAST, OR THE EXCLUSION OR LIMITATION OF 
 *   INCIDENTAL OR CONSEQUENTIAL DAMAGES,
 *   SO THE ABOVE LIMITATIONS OR EXCLUSIONS MAY NOT APPLY TO YOU. 
 *   THIS WARRANTY GIVES YOU SPECIFIC LEGAL RIGHTS AND YOU MAY ALSO 
 *   HAVE OTHER RIGHTS WHICH VARY FROM JURISDICTION TO JURISDICTION.
 *
 *
 *   6. SEVERABILITY
 *   In the event of invalidity of any provision of this license, the parties agree that such invalidity shall not
 *   affect the validity of the remaining portions of this license.
 *
 *
 *   7. NO LIABILITY FOR CONSEQUENTIAL DAMAGES IN NO EVENT SHALL DEVLAN SOLUTIONS LTD OR ITS SUPPLIERS BE LIABLE TO YOU FOR ANY
 *   CONSEQUENTIAL, SPECIAL, INCIDENTAL OR INDIRECT DAMAGES OF ANY KIND ARISING OUT OF THE DELIVERY, PERFORMANCE OR 
 *   USE OF THE SOFTWARE, EVEN IF DEVLAN SOLUTIONS LTD HAS BEEN ADVISED OF THE POSSIBILITY OF SUCH DAMAGES
 *   IN NO EVENT WILL DEVLAN SOLUTIONS LTD  LIABILITY FOR ANY CLAIM, WHETHER IN CONTRACT 
 *   TORT OR ANY OTHER THEORY OF LIABILITY, EXCEED THE LICENSE FEE PAID BY YOU, IF ANY.
 *
 */

require_once('../vendor/autoload.php');

use Dompdf\Dompdf;

$dompdf = new Dompdf();

$html =
    '
        <!DOCTYPE html>
            <html>
                <head>
                    <meta name="" content="XYZ,0,0,1" />
                    <style type="text/css">
                        table {
                            font-size: 12px;
                            padding: 4px;
                        }

                        th {
                            text-align: left;
                            padding: 4pt;
                        }

                        td {
                            padding: 5pt;
                        }

                        #b_border {
                            border-bottom: dashed thin;
                        }

                        legend {
                            color: #0b77b7;
                            font-size: 1.2em;
                        }

                        #error_msg {
                            text-align: left;
                            font-size: 11px;
                            color: red;
                        }

                        .header {
                            margin-bottom: 20px;
                            width: 100%;
                            text-align: left;
                            position: absolute;
                            top: 0px;
                        }

                        .footer {
                            width: 100%;
                            text-align: center;
                            position: fixed;
                            bottom: 5px;
                        }

                        #no_border_table {
                            border: none;
                        }

                        #bold_row {
                            font-weight: bold;
                        }

                        #amount {
                            text-align: right;
                            font-weight: bold;
                        }

                        .pagenum:before {
                            content: counter(page);
                        }

                        /* Thick red border */
                        hr.red {
                            border: 1px solid red;
                        }
                        .list_header{
                            font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
                        }
                    </style>
                </head>

                <body style="margin:1px;">
                    <div class="list_header" align="center">
                        <h3>
                        <img src="'.$base64.'" width="290" alt="Logo"> <br> <br>
                            The Ultimate Car Rental Information Management System
                        </h3>                        
                        <hr style="width:100%" , color=black>
                        <h5>System Users Reports - Generated On '.date('d M Y').'</h5>
                    </div>
                    <table border="1" cellspacing="0" width="98%" style="font-size:9pt">
                        <thead>
                            <tr>
                                <th style="width:20%">S/N</th>
                                <th style="width:50%">Number</th>
                                <th style="width:100%">Full Names</th>
                                <th style="width:50%">ID Number</th>
                                <th style="width:80%">Phone Number</th>
                                <th style="width:100%">Email Address</th>
                                <th style="width:50%">Access Level</th>
                                <th style="width:100%">Physical Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            ';
                            $users_sql = mysqli_query(
                                $mysqli,
                                "SELECT * FROM users"
                            );
                            $cnt = 1;
                            if (mysqli_num_rows($users_sql) > 0) {
                                while ($users = mysqli_fetch_array($users_sql)) {
                                    $html .=
                                        '
                                            <tr>
                                                <td>' . $cnt . '</td>
                                                <td>' . $users['user_number'] . '</td>
                                                <td>' . $users['user_name'] . '</td>
                                                <td>' . $users['user_id_number'] . '</td>
                                                <td>' . $users['user_phone_number'] . '</td>
                                                <td>' . $users['user_email'] . '</td>
                                                <td>' . $users['user_access_level'] . '</td>
                                                <td>' . $users['user_address'] . '</td>
                                            </tr>
                                        ';
                                    $cnt = $cnt + 1;
                                }
                            }
                            $html .= '
                        </tbody>
                    </table>
                </body>
            </html>
    ';
$dompdf = new Dompdf();
$dompdf->load_html($html);
$dompdf->set_paper('A4', 'landscape');
$dompdf->set_option('isHtml5ParserEnabled', true);
$dompdf->render();
$dompdf->stream('Users Reports Generated On'. date('d M Y'), array("Attachment" => 1));
$options = $dompdf->getOptions();
$options->setDefaultFont('');
$dompdf->setOptions($options);