<footer class="nhsuk-footer" role="contentinfo">
    <div class="nhsuk-width-container">
        <div class="nhsuk-footer__meta">
            <h2 class="nhsuk-u-visually-hidden">Support links</h2>

            <ul class="nhsuk-footer__list">
                <li class="nhsuk-footer__list-item nhsuk-footer-default__list-item">
                    <a class="nhsuk-footer__list-item-link" href="{{ config('app.corporate_accessibility_url') }}">Accessibility</a>
                </li>
                <li class="nhsuk-footer__list-item nhsuk-footer-default__list-item">
                    <a class="nhsuk-footer__list-item-link" href="{{ config('app.corporate_tac_url') }}">Terms and conditions</a>
                </li>
                <li class="nhsuk-footer__list-item nhsuk-footer-default__list-item">
                    <a class="nhsuk-footer__list-item-link" href="{{ config('app.corporate_privacy_url') }}">Privacy and cookies</a>
                </li>
                <li class="nhsuk-footer__list-item nhsuk-footer-default__list-item">
                    <a class="nhsuk-footer__list-item-link" href="{{ config('app.contact_us_url') }}">Contact us</a>
                </li>
            </ul>
            <p class="nhsuk-body-s">
                {{ config('app.footer_copyright_text') }} <span class="nhsuk-u-visually-hidden">v<?php echo config('app.version'); ?></span>
            </p>
        </div>
    </div>
</footer>
