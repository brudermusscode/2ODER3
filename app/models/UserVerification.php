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
    "ip"
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

    # E-Mail of invalid format?
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
      return error("Bruder, E-Mail ist falsch 😆");

    # E-Mail already associated with another User?
    if (User::where("email", $email)->first())
      return error("Bruder, E-Mail ist schon in-use 😆");

    $code = Utils::random_numeric_token(4);
    $token = Utils::random_alpha_token(32);

    $update_to = [
      "email" => $email,
      "token" => $token,
      "code" => $code,
      "ip" => Request::get_remote_address(),
    ];

    # # Update or create it!
    $User->verification?->update($update_to) ??
      $User->verification()->create($update_to);

    # Prepare mail body.
    $mail_body = file_get_contents(
      TEMPLATE . "/mail/user-verification.html",
    );
    $mail_body = str_replace("%code%", $code, $mail_body);

    # Send a mail with a verification code.
    (new Mail())->create(
      address: $email,
      subject: "d1 code ist: 🫱 $code",
      body: $mail_body,
    );

    return success(
      data: [
        "token" => $token,
        "email" => $email,
      ],
    );
  }

  /**
   * @param object $params
   * @return string
   */
  public function edit(object $params)
  {

    /**
     * @var Visitor
     */
    $Visitor = $params->Client;

    $this->user->update([
      "email" => $this->email,
      "email_verified" => true,
    ]);

    $this->delete();

    # Boooom, all done. Now we can transform the Visitor
    # into a User and clean up all Visitor relations!
    # Sooooo gooooooooooooooooooooooooooooooooooooooood.
    $Visitor->transform_into($this->user);

    # Create a new User session. All is done!
    (new Session())->new($this->user);

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
