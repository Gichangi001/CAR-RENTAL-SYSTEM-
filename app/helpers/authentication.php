<?php
/*
 *   Crafted On Tue Feb 21 2023
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


/* Login */
if (isset($_POST['Login'])) {
    $login_email = mysqli_real_escape_string($mysqli, $_POST['login_email']);
    $login_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['login_password'])));

    /* Login Client First */
    $client_login_sql = "SELECT * FROM clients WHERE client_email = '{$login_email}' AND client_password = '{$login_password}'";
    $res = mysqli_query($mysqli, $client_login_sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        $_SESSION['client_id'] = $row['client_id'];
        $_SESSION['client_dpic'] = $row['client_dpic'];
        $_SESSION['user_access_level'] = 'Client';
        $_SESSION['success'] = 'Logged in successfully';
        header('Location: home');
        exit;
    } else if (mysqli_num_rows($res) == 0) {
        /* Staff Login */
        $staff_login_sql = "SELECT * FROM users WHERE user_email = '{$login_email}' AND user_password = '{$login_password}'";
        $res = mysqli_query($mysqli, $staff_login_sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_access_level'] = $row['user_access_level'];
            $_SESSION['user_dpic'] = $row['user_dpic'];
            $_SESSION['success'] = 'Login successful';
            header('Location: dashboard');
            exit;
        } else {
            $err = "Invalid login credentials";
        }
    } else {
        $err = "Invalid login credentials";
    }
}

/* Register */
if (isset($_POST['Register'])) {
    $client_names = mysqli_real_escape_string($mysqli, $_POST['client_names']);
    $client_id_no = mysqli_real_escape_string($mysqli, $_POST['client_id_no']);
    $client_email = mysqli_real_escape_string($mysqli, $_POST['client_email']);
    $client_phone_number = mysqli_real_escape_string($mysqli, $_POST['client_phone_number']);
    $client_address = mysqli_real_escape_string($mysqli, $_POST['client_address']);
    $password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['new_password'])));
    $confirm_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password'])));
    $client_date_joined = mysqli_real_escape_string($mysqli, date('Y-m-d H:i:s'));

    /* Check If Passwords Match */
    if ($password != $confirm_password) {
        $err = "Passwords Do Not Match";
    } else {
        /* Avoid Redundancy */
        $duplication_sql = "SELECT * FROM  clients WHERE client_email = '{$client_email}'
        || client_phone_number = '{$client_phone_number}' || client_id_no = '{$client_id_no}'";
        $res = mysqli_query($mysqli, $duplication_sql);
        if (mysqli_num_rows($res) > 0) {
            $err = "An account with this email, phone or ID number already exists";
        } else {
            /* Persist */
            $register_sql = "INSERT INTO clients (client_names, client_id_no, client_email, client_phone_number, client_address, client_password, client_date_joined)
            VALUES ('{$client_names}', '{$client_id_no}', '{$client_email}', '{$client_phone_number}', '{$client_address}', '{$password}', '{$client_date_joined}')";

            /* Mailer */
            include('../app/mailers/sign_up_mailer.php');
            //include('../app/sms/welcome.php');
            if (mysqli_query($mysqli, $register_sql) && $mail->send()) {
                $_SESSION['success'] = 'Account created successfully';
                header('Location: login');
                exit;
            } else {
                $err = "Failed, please try again later";
            }
        }
    }
}

/* Reset Password Step 1 */
if (isset($_POST['Reset_Password_Step_1'])) {
    $login_email = mysqli_real_escape_string($mysqli, $_POST['login_email']);
    $reset_url = $url . $tk;
    /* Check If Email Exists */
    $sql = "SELECT * FROM clients WHERE client_email = '{$login_email}'";
    $res = mysqli_query($mysqli, $sql);
    if (mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
        /* Get User Email Where Reset Link Will Be Sent */
        $email = $row['client_email'];

        /* Persist reset code  */
        $reset_code_sql = "UPDATE clients SET client_password_reset_code = '{$tk}',
        client_password = '' WHERE client_email = '{$login_email}'";
        if (mysqli_query($mysqli, $reset_code_sql)) {
            /* Mailer */
            include('../app/mailers/reset_password.php');
            if ($mail->send()) {
                $success = 'Please check your email for a password reset link';
            } else {
                $info = "We could not send you a password reset link, please try again";
            }
        } else {
            $err = "Failed, please try again later";
        }
    } else if (mysqli_num_rows($res) == 0) {
        /* Reset Staff Password*/
        $sql = "SELECT * FROM users WHERE user_number = '{$login_email}' || user_email = '{$login_email}'";
        $res = mysqli_query($mysqli, $sql);
        if (mysqli_num_rows($res) > 0) {
            $row = mysqli_fetch_assoc($res);
            /* Get User Email Where Reset Link Will Be Sent */
            $email = $row['user_email'];

            /* Persist reset code  */
            $reset_code_sql = "UPDATE users SET user_password_reset_code = '{$tk}',
            user_password = '' WHERE user_number = '{$login_email}' || user_email = '{$login_email}'";
            if (mysqli_query($mysqli, $reset_code_sql)) {
                /* Mailer */
                include('../app/mailers/reset_password.php');
                if ($mail->send()) {
                    $success = 'Please check your email for a password reset link';
                } else {
                    $info = "We could not send you a password reset link, please try again";
                }
            } else {
                $err = "Failed, please try again later";
            }
        } else {
            $err = "No account with this email or staff number exists";
        }
    } else {
        $err = "No account with this email  exists";
    }
}

/* Reset Password Step 2 */
if (isset($_POST['Reset_Password_Step_2'])) {
    $new_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['new_password'])));
    $confirm_password = sha1(md5(mysqli_real_escape_string($mysqli, $_POST['confirm_password'])));
    $token = mysqli_real_escape_string($mysqli, $_GET['token']);

    if (!empty($token)) {
        /* Check if passwords match */
        if ($new_password != $confirm_password) {
            $err = "Passwords Do Not Match";
        } else {
            /* Check Whos account has this token */
            $sql = "SELECT * FROM clients WHERE client_password_reset_code = '{$token}'";
            $res = mysqli_query($mysqli, $sql);
            if (mysqli_num_rows($res) > 0) {
                /* Update Password */
                $update_sql = "UPDATE clients SET client_password = '{$new_password}',
            client_password_reset_code = '' WHERE client_password_reset_code = '{$token}'";
                if (mysqli_query($mysqli, $update_sql)) {
                    $_SESSION['success'] = "Password reset successful";
                    header('Location: login');
                    exit;
                } else {
                    $err = "Failed, please try again later";
                }
            } else if (mysqli_num_rows($res) == 0) {
                /* Check Whos account has this token */
                $sql = "SELECT * FROM users WHERE user_password_reset_code = '{$token}'";
                $res = mysqli_query($mysqli, $sql);
                if (mysqli_num_rows($res) > 0) {
                    /* Update Password */
                    $update_sql = "UPDATE users SET user_password = '{$new_password}',
                    user_password_reset_code = '' WHERE user_password_reset_code = '{$token}'";
                    if (mysqli_query($mysqli, $update_sql)) {
                        $_SESSION['success'] = "Password reset successful";
                        header('Location: login');
                        exit;
                    } else {
                        $err = "Failed, please try again later";
                    }
                } else {
                    $_SESSION['err'] = "Invalid password reset token";
                    header('Location: reset_password');
                    exit;
                }
            } else {
                $_SESSION['err'] = "Invalid password reset token";
                header('Location: reset_password');
                exit;
            }
        }
    } else {
        $_SESSION['err'] = "Invalid password reset token";
        header('Location: reset_password');
        exit;
    }
}
