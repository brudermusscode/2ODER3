<?php

namespace Bruder\Model;

use Bruder\Bruder;
use Bruder\Http\Request;
use Bruder\Mail\Mail;
use Bruder\Utils\Utils;

class UserVerification extends Bruder
{

  /**
   * @var array
   */
  protected $fillable = [
    "email",
    "token",
    "code",
    "ip",
  ];

  /**
   * @param object $params
   * @return string
   */
  public function new(object $params)
  {

    /**
     * @var User
     */
    $User = $params->User;

    /**
     * ? E-Mail
     */
    $email = trim($params->email);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      return error("Bruder, E-Mail ist falsch 😆");

    $code = Utils::random_numeric_token(4);
    $token = Utils::random_alpha_token(32);

    $update_to = [
      "email" => $email,
      "token" => $token,
      "code" => $code,
      "ip" => Request::get_remote_address(),
    ];

    # # Update or create it!
    $User->verification?->update($update_to)
      ?? $User->verification()->create($update_to);

    # Prepare mail body.
    $mail_body = file_get_contents(TEMPLATE . "/mail/user-verification.html");
    $mail_body = str_replace("%code%", $code, $mail_body);

    # Send a mail with a verification code.
    (new Mail)->create(
      address: $email,
      subject: "🫱 Bruder, d1 code ist: $code",
      body: $mail_body,
    );

    return success(data: [
      "token" => $token,
      "email" => $email,
    ]);
  }

  /**
   * @param object $params
   * @return string
   */
  public function edit()
  {

    $this->user->update([
      "email" => $this->email,
      "email_verified" => true,
    ]);

    $this->delete();

    # We can use the User for authorization now, so clean up all
    # Visitor relatives.
    Visitor::clean_up();

    # Create a new User session. All is done!
    $Session = (new Session)->new($this->user);

    return success();
  }

  /**
   * @return User
   */
  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
