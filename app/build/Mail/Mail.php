<?php

namespace Bruder\Mail;

use Bruder\Application\Logger;
use PHPMailer\PHPMailer\PHPMailer;

class Mail
{

  /**
   * @param string $address
   * @param string $subject
   * @param mixed $body - Either HTML or text.
   * @param ?string $from_mail - Set where the mail should be
   *                coming from.
   * @param ?string $from_name - Set the name the mail should be
   *                coming from.
   * @param bool $debug
   * @return boolean
   */
  public function create(
    string $address,
    string $subject,
    mixed $body,
    ?string $from_mail = null,
    ?string $from_name = null,
    bool $debug = false
  ) {

    $private_key = _root() . "/config/keys/DKIM.key";
    $mail = new PHPMailer(true);
    $config = _env();

    # DKIM key missing in production?
    if (!file_exists($private_key) && current_env() !== "dev")
      return false;

    # Important.
    date_default_timezone_set('Europe/Berlin');

    try {

      # ? Basic configuration
      $mail->isSMTP();
      $mail->Host = $config->MAIL_HOST;
      $mail->Port = $config->MAIL_PORT;
      $mail->Priority = 1;
      $mail->SMTPAuth = $config->MAIL_ENABLE_AUTH;
      $mail->Username = $config->MAIL_USERNAME;
      $mail->Password = $config->MAIL_PASSWORD;

      # ? Receipient & Content
      $mail->setFrom(
        $from_mail ?? $config->MAIL_FROM_MAIL,
        $from_name ?? $config->MAIL_FROM_NAME
      );
      $mail->Subject = $subject;
      $mail->isHTML(true);
      $mail->Body = $body;
      // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

      $mail->addAddress($address);

      # ? Encoding
      $mail->CharSet = "UTF-8";
      $mail->Encoding = "base64";

      # ? Debugging
      if ($debug) {
        $mail->Debugoutput = "echo";
        $mail->SMTPDebug = 4; // Enable full debug output
      }

      # * In Production
      if (current_env() !== "dev") {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        # ? DKIM setup
        $mail->DKIM_domain = $config->DOMAIN;
        $mail->DKIM_selector = $config->MAIL_DKIM_SELECTOR;
        $mail->DKIM_private = $private_key;
        $mail->DKIM_passphrase = $config->MAIL_DKIM_PASSPHRASE;
        $mail->DKIM_identity = $mail->From;
      }

      # # Send!
      $mail->send();

      return true;
    } catch (\Exception $e) {
      Logger::to_file($e, "mail_errors.log");

      return false;
    }
  }
}
