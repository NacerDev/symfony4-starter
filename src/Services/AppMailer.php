<?php

namespace App\Services ;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use FOS\UserBundle\Mailer\MailerInterface; 

class AppMailer implements MailerInterface{


    /**
     * @var UrlGeneratorInterface
     */
    protected $router;

    /** 
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var array
     */
    protected $parameters;
    
    protected $mailer;

    /**
     * TwigSwiftMailer constructor.
     *
     * @param UrlGeneratorInterface $router
     * @param \Twig_Environment     $twig
     * @param array                 $parameters
     */
    public function __construct(UrlGeneratorInterface $router, \Twig_Environment $twig, array $parameters)
    {
     
        $this->router = $router;
        $this->twig = $twig;
        $this->parameters = $parameters;
        
        $this->mailer= new PHPMailer(true);
            //Server settings
        $this->mailer->SMTPDebug = 0;                                 // Enable verbose debug output
        $this->mailer->isSMTP();                                      // Set mailer to use SMTP
        $this->mailer->Host = getenv("MAILER_HOST");                         // Specify main and backup SMTP servers
        $this->mailer->SMTPAuth = true;                               // Enable SMTP authentication
        $this->mailer->Username = getenv("MAILER_USER_ADRESS");               // SMTP username
        $this->mailer->Password = getenv("MAILER_USER_PASSWORD");                  // SMTP password
        $this->mailer->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
        $this->mailer->Port = getenv("MAILER_PORT");                                    // TCP port to connect to
        $this->mailer->isHTML(true);                                  // Set email format to HTML
    }

    public function sendConfirmationEmailMessage(UserInterface $user) {
        $template = $this->parameters['template']['confirmation'];
        $url = $this->router->generate('fos_user_registration_confirm', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
        );
        $this->sendMessage($template, $context, $this->parameters['from_email']['confirmation'], (string) $user->getEmail());
    }

    public function sendResettingEmailMessage(UserInterface $user) {
        
        $template = $this->parameters['template']['resetting'];
        $url = $this->router->generate('fos_user_resetting_reset', array('token' => $user->getConfirmationToken()), UrlGeneratorInterface::ABSOLUTE_URL);

        $context = array(
            'user' => $user,
            'confirmationUrl' => $url,
        );
        $this->sendMessage($template, $context, $this->parameters['from_email']['resetting'], (string) $user->getEmail());
    }

         /**
     * @param string $templateName
     * @param array  $context
     * @param array  $fromEmail
     * @param string $toEmail
     */
    protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
    {
        
        $template = $this->twig->load($templateName);
        $htmlBody = $template->render($context);

        $this->mailer->Subject="No Reply";
        $this->mailer->Body=$htmlBody;
        $this->mailer->setFrom(array_values($fromEmail)[0]);
        $this->mailer->addAddress($toEmail);
        $this->mailer->send();
    }
}