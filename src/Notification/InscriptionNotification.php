<?php
    namespace App\Notification;

    use App\Entity\Membre;
    use Twig\Environment;

    class InscriptionNotification
    {
        /**
         * @var \Swift_Mailer
         */
        private $mailer;
        /**
         * @var Environment
         */
        
        private $renderer;
        public function __construct(\Swift_Mailer $mailer, Environment $renderer)
        {
            $this->mailer = $mailer;
            $this->renderer = $renderer;
        }
        public function notify(Membre $inscription)
        {
            $message = (new \Swift_Message('Message : '))
            ->setFrom('axel.mampiono@gmail.com')
            ->setTo($inscription->getEmail())
            ->setReplyTo('axel.mampiono@gmail.com')
            ->setBody($this->renderer->render('emails/inscription.html.twig', [
            'inscription' => $inscription
            ]), 'text/html');
            $this->mailer->send($message);
        }
    }

?>