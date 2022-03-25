<style type="text/css">
#new-cookie-consent-container {
    /*overflow: hidden;*/
    position: fixed;
    bottom: 0px;
    left: 0px;
    /*display: none;*/
    z-index: 9999;
}

.cookie-consent-banner {
    width: calc(100% - 64px);
    min-height: 142px;
    margin: 32px;
    padding: 35px 40px;
    overflow: hidden;
    box-sizing: border-box;
    border-radius: 5px;
    border-width: 0;
    background-color: rgb(52, 73, 94);
    box-shadow: 0 0 35px 0 rgba(0,0,0,.2);
    left: 0;
}
.cookie-consent-banner p {
    font-family: Helvetica;
    font-size: 12px;
    color: rgb(255, 255, 255);
    margin-bottom: 20px;
    justify-content: space-between;
}
.cookie-consent-banner a {
    color: rgb(255, 255, 255);
    text-decoration: underline;
}

.new-style-button {
    font-family: Helvetica, Open Sans,Arial;
    font-size: 12px;
    color: #fff;
    line-height: 1.5;
    padding: 9px;
    cursor: pointer;
    outline: none;
    letter-spacing: .5px;
    border-radius: 3px;
    background: transparent;
    margin-right: 10px;
    min-width: 190px;
    border: 1px solid rgba(255, 255, 255, 0.5);
}
.new-style-button.btn-light-bg{
    background: rgb(255, 255, 255); color: rgb(51, 51, 51);
}
</style>

<div id="new-cookie-consent-container">
    <div class="cookie-consent-banner">
        <p>
            {!! trans('app.cookie_consent_message') !!}
            <a href="{{ get_page_url(\App\Models\Page::PAGE_PRIVACY_POLICY) }}" target="_blank">{{ trans('app.cookies_terms') }}</a>
        </p>

        <div class="pull-right">
            <button class="new-style-button mb-2" id="cookie-consent-button" type="button">
                {{ trans('app.cookie_consent_agree') }}
            </button>

            <button class="new-style-button btn-light-bg mb-2" id="cookie-cancel-button" type="button">
                {{ trans('app.cancel') }}
            </button>
        </div>
    </div>
</div>

<script>
    window.gdprCookieConsent = (function () {
        const COOKIE_VALUE = 1;
        const COOKIE_DOMAIN = '{{ config('session.domain') ?? request()->getHost() }}';

        function consentWithCookies() {
            hideCookieDialog();
            setCookie('{{ config('gdpr.cookie.name') }}', COOKIE_VALUE, {{ config('gdpr.cookie.lifetime') }});
        }

        function cookieExists(name) {
            return (document.cookie.split('; ').indexOf(name + '=' + COOKIE_VALUE) !== -1);
        }

        function hideCookieDialog() {
            const dialogs = document.getElementById('new-cookie-consent-container');

            if(dialogs) {
                dialogs.style.display = 'none';
            }
        }

        function setCookie(name, value, expirationInDays) {
            const date = new Date();
            date.setTime(date.getTime() + (expirationInDays * 24 * 60 * 60 * 1000));
            document.cookie = name + '=' + value
                + ';expires=' + date.toUTCString()
                + ';domain=' + COOKIE_DOMAIN
                + ';path=/{{ config('session.secure') ? ';secure' : null }}';
        }

        if (cookieExists('{{ config('gdpr.cookie.name') }}')) {
            hideCookieDialog();
        }

        const consentButton = document.getElementById('cookie-consent-button');
        const cancelButton = document.getElementById('cookie-cancel-button');

        if(consentButton) {
            consentButton.addEventListener('click', consentWithCookies);
        }

        if(cancelButton) {
            cancelButton.addEventListener('click', hideCookieDialog);
        }

        return {
            consentWithCookies: consentWithCookies,
            hideCookieDialog: hideCookieDialog
        };
    })();
</script>