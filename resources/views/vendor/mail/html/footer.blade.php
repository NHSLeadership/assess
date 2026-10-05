<tr>
<td>
<table class="footer" align="center" width="570" cellpadding="0" cellspacing="0" role="presentation">
<tr>
<td class="content-cell" align="center">
    <p style="text-align: center; color: #4c6272">
        <strong>{{ config('app.organisation') }}</strong>
    </p>
    <p style="font-size: 12px; font-family: Arial, Helvetica, sans-serif; color: #4c6272;">
        <a style="color: #4c6272;" href="{{ config('app.org_url') }}">{{ parse_url(config('app.org_url'), PHP_URL_HOST) }}</a>
    </p>
    {{ Illuminate\Mail\Markdown::parse($slot) }}
</td>
</tr>
</table>
</td>
</tr>
