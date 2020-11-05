<?php


namespace Core\Controller;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Mailer\MailerInterface;

class EmailController extends controller
{

    private $mail;

    /**
     * EmailController constructor.
     * @param string|null $objet
     */
    public function __construct(string $objet = null)
    {

        $mail = new PHPMailer();
        $mail->isSMTP();
        if ($this->getConfig('ENV_DEV')) {
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Host         = 'localhost';
            $mail->Port         = '1025';
            $sender = ["mail@test.fr", "mail test"];
        } else {
            $mail->Host         = $this->getConfig('MAILER_Host');
            $mail->SMTPAuth     = true;
            $mail->Username     = $this->getConfig('MAILER_Username');
            $mail->Password     = $this->getConfig('MAILER_Password');
            $mail->SMTPSecure   = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port         = $this->getConfig('MAILER_Port');
            $sender = [$this->getConfig('MAILER_Username'), $this->getConfig('stieName')];
        }
        $mail->setFrom($sender[0], $sender[1]);
        $this->mail = $mail;
    }


    public function object(string $objet): self
    {
        $this->mail->Subject = $objet;
        return $this;
    }

    public function to(string $mail): self
    {
        $this->mail->addAddress($mail);
        return $this;
    }

    public function message(string $template, array $data = null): self
    {

        $this->mail->isHTML(true);
        $this->mail->Body = $this->render("email/" . $template . ".html", $data);
        $this->mail->AltBody = $this->render("email/" . $template . ".txt", $data);
        return $this;
    }


    public function send(): void
    {
        try {
            $this->mail->send();
        } catch (\Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
