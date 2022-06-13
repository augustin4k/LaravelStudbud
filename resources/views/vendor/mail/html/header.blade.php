<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'StudBud')
<img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Font_Awesome_5_solid_graduation-cap.svg/1279px-Font_Awesome_5_solid_graduation-cap.svg.png" class="logo" alt="Laravel Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
