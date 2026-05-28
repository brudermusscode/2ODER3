<?php

# TODO: Middleware please.
if (LOGGED) :
  include UNAVAILABLE;
else : ?>

  <content login fl alic>
    <div pinline32 pblock42
      style="backdrop-filter:blur(6px);background:rgba(0,0,0,.42);border-block:1px solid rgba(255,255,255,.12);max-height:calc(100vh - 24px);max-width:380px;width:100%;" ovauto rounded=wide>

      <form request="session:create" full-reload redirect="/" responder=simple
        fl fldircol gap>
        <p login-sub text midplus bold tac mb24 mt24>Einloggen</p>

        <div fl fldircol gap=smol>
          <input login autofocus tabindex=1 type=text name="nickname" placeholder="Nickname" />
          <input login tabindex=2 type=password name="password" placeholder="Passwort" />
        </div>

        <div fl jucsb alic mt24>
          <a onclick="history.go(-1)" normal>
            <mbutton tabindex=4 mid no-hover-shadow>
              <span color=tertiary>zurück</span>
            </mbutton>
          </a>
          <mbutton tabindex=3 submit-closest wide background=green icon-only>
            <mi>arrow_forward</mi>
          </mbutton>
        </div>
      </form>
    </div>
  </content>

<?php endif; ?>