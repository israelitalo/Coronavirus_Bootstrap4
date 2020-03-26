<?php
    session_start();
    if(empty($_SESSION['id_adm'])){
        ?>
        <script type="text/javascript">window.location.href="sair.php";</script>
        <?php
    }

    require_once __DIR__ . '/vendor/autoload.php';

    use Classes\Usuarios\Usuarios;
    use Classes\Usuarios\UsuarioDao;

    if(isset($_POST['nome']) && !empty($_POST['nome']) && isset($_POST['login']) && !empty($_POST['login'])
        && isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['senha']) && !empty($_POST['senha'])) {

        /*require 'Classes/Usuarios/Usuarios.php';
        require_once 'Classes/Usuarios/UsuarioDao.php';*/

        $usuario = new Usuarios();
        $ud = new UsuarioDao();

        $usuario->setNome(addslashes($_POST['nome']));
        $usuario->setLogin(addslashes($_POST['login']));
        $usuario->setEmail(addslashes($_POST['email']));
        $usuario->setSenha(addslashes(md5($_POST['senha'])));
        $usuario->setIdHospital(addslashes($_POST['hospital']));
        $usuario->setTelefone(addslashes($_POST['telefone']));

        if($ud->addUsuario($usuario->getNome(), $usuario->getLogin(), $usuario->getEmail(), $usuario->getSenha(),
            $usuario->getIdHospital(), $usuario->getTelefone()) == true){

            //Código para poder enviar e-mail pela WebHost
            /*use PHPMailer\PHPMailer\PHPMailer;
            use PHPMailer\PHPMailer\Exception;*/

            /*
             * Tentativa de subtituit os 2 uses feitos acima. Tentativa feita por Israel.
            require_once 'mail/PHPMailer-master/PHPMailer.php';
            require_once 'mail/PHPMailer-master/Exception.php';
            require_once 'mail/PHPMailer-master/SMTP.php';
            */

            //Restante do código será comentado.
            /*require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer-master/Exception.php';
            require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer-master/PHPMailer.php';
            require $_SERVER['DOCUMENT_ROOT'] . '/mail/PHPMailer-master/SMTP.php';

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = 2; // 0 = off (for production use) - 1 = client messages - 2 = client and server messages
            $mail->Host = "smtp.gmail.com"; // use $mail->Host = gethostbyname('smtp.gmail.com'); // if your network does not support SMTP over IPv6
            $mail->Port = 587; // TLS only
            $mail->SMTPSecure = 'tls'; // ssl is deprecated
            $mail->SMTPAuth = true;
            $mail->Username = 'youremail@gmail.com'; // email
            $mail->Password = 'PASSWORD'; // password
            $mail->setFrom('system@cksoftwares.com', 'CKSoftwares System'); // From email and name
            $mail->addAddress('to@address.com', 'Mr. Brown'); // to email and name
            $mail->Subject = 'PHPMailer GMail SMTP test';
            $mail->msgHTML("test body"); //$mail->msgHTML(file_get_contents('contents.html'), __DIR__); //Read an HTML message body from an external file, convert referenced images to embedded,
            $mail->AltBody = 'HTML messaging not supported'; // If html emails is not supported by the receiver, show this body
            // $mail->addAttachment('images/phpmailer_mini.png'); //Attach an image file
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            if(!$mail->send()){
                echo "Mailer Error: " . $mail->ErrorInfo;
            }else{
                echo "Message sent!";
            }
            //Fim código WEBHost.*/

            /*$id = $ud->getIdUserEmail($usuario->getEmail());

            $idMd5 = md5($id['id']);

            $link = 'http://www.israelitalo.ml/cadastroconfirma/confirmar.php?h='.$idMd5;

            $assunto = "Confirme seu cadastro no CvSoftware";
            $msg = "Valide seu cadastro no link abaixo:\n\n".$link;
            $headers = "From: israelitalo2012@gmail.com"."\r\n".
                "X-Mailer: PHP/".phpversion();

            mail($usuario->getEmail(), $assunto, $msg, $headers);*/

            $_SESSION['msg'] = "Usuário cadastrado com sucesso.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-usuarios.php";</script>
            <?php
        }else{
            $_SESSION['msg'] = "Erro ao cadastrar usuário.";
            ?>
            <script type="text/javascript">window.location.href="gerenciar-usuarios.php";</script>
            <?php
        }




    }
?>
