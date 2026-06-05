@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
<img src="{{ asset('portal/assets/images/app-logo.svg') }}" class="logo" alt="{{ config('app.name') }} Logo">
<span class="logo-text">{{ config('app.name') }}</span>
</a>
</td>
</tr>
