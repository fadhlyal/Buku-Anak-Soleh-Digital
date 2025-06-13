<div class="letterhead">
    @php
        $isLocal = app()->environment('local');
        $logoSrc = $isLocal ? public_path('Logo.png') : asset('Logo.png');
    @endphp
    
    <table style="width: 100%; border: none;">
        <tr>
            <td style="width: 80px; text-align: right; vertical-align: middle; border: none;">
                <img src="{{ $logoSrc }}" alt="Logo" style="width: 60px;">
            </td>
            <td style="text-align: right; vertical-align: middle; border: none;">
                <h2 style="margin: 0; font-size: 16px;">SD AR RAFI 1</h2>
                <p style="margin: 0; font-size: 12px;">Jl. Sekejati 3 No.20, Sukapura, Kec. Kiaracondong, Kota Bandung, Jawa Barat 40285</p>
                <p style="margin: 0; font-size: 12px;">Telp. (022) 7311009</p>
            </td>
        </tr>
    </table>

    <!-- Double horizontal line -->
    <hr style="border: 1px solid black; margin-top: 10px;">
    <hr style="border: 2px solid black; margin-top: -5px; margin-bottom: 10px;">
</div>