<?php


namespace Core\Controller;

class EmailController extends controller
{

    private $mailservice;
    private $message;

    /**
     * EmailController constructor.
     * @param string|null $objet
     */
    public function __construct(string $objet = null)
    {

        if (getenv('ENV_DEV')) {
            $transport = new \Swift_SmtpTransport('mailcatcher', 1025);
            $sender = ["mail@test.fr" => "mail test"];
        } else {
            $transport = new \Swift_SmtpTransport('smtp.gmail.com', 587, 'tls');
            $transport->setUsername(getenv('GMAIL_USER'));
            $transport->setPassword(getenv('GMAIL_PWD'));
            $sender = [
                getenv('GMAIL_USER') => getenv('GMAIL_PSEUDO')
            ];
        }

        $this->mailservice = new \Swift_Mailer($transport);
        $message = new \Swift_Message($objet);
        $message->setFrom($sender);

        $headers = $message->getHeaders();
        $headers->addMailboxHeader('From', $sender);
        $headers->addMailboxHeader('Reply-to', $sender);
        $this->message = $message;
    }


    public function object(string $objet): self
    {
        $this->message->setSubject($objet);
        return $this;
    }

    public function to(string $mail): self
    {
        $this->message->setTo($mail);
        return $this;
    }

    public function message(string $template, array $data = null): self
    {

        $this->message->setBody($this->render("email/" . $template . ".html", $data), 'text/html');
        $this->message->addPart($this->render("email/" . $template . ".txt", $data), 'text/plain');
        return $this;
    }


    public function send(): void
    {
        $this->mailservice->send($this->message);
    }
}
