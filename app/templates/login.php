<?php

# TODO: Middleware please.
if (LOGGED) :
  include UNAVAILABLE;
else : ?>

  <div fl alic jucstart>
    <div style="top:50%;left:50%;translate:-50% -50%;" posabs>
      <div window pinline32 pblock42
        style="max-height:calc(100vh - 24px);max-width:400px;width:100%;" ovauto">

        <form request="session:create" full-reload redirect="/" responder=simple
          fl fldircol gap>
          <div fl fldircol alic>
            <a href="/">
              <logo mid>
                <picture circled>
                  <img src="/jesus.webp" />
                </picture>
              </logo>
            </a>
            <p text midler ttup z style="text-shadow:0 -1px 4px rgba(0,0,0,.62);">
              <strong>DEV</strong>Log
            </p>
          </div>

          <div fl fldircol gap=smol>
            <input login autofocus tabindex=1 type=text name="nickname" placeholder="Nickname" />
            <input login tabindex=2 type=password name="password" placeholder="Passwort" />
          </div>

          <div fl jucsb alic mt24>
            <a onclick="history.go(-1)" normal>
              <mbutton tabindex=4 stdplus no-hover-shadow>
                <span color=tertiary>züröck</span>
              </mbutton>
            </a>
            <mbutton tabindex=3 submit-closest stdplus background=primary
              color=primary-text icon-only>
              <mi>arrow_forward</mi>
            </mbutton>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php endif; ?>