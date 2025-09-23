@include('mail.header')
@include('mail.headerbanner')




  <tr>
    <td align="center" valign="top"  style="mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;border: 0;padding: 0;">
  <!--[if mso]>
  <table align="center" border="0" cellspacing="0" cellpadding="0" width="600" style="width:600px;">
  <tr>
  <td align="center" valign="top" width="600" style="width:600px;">
  <![endif]-->
  <table align="center" lang=x-outer class="x-outer"  border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;width: 100%;background-color: #FFF;max-width:600px;width:100%;margin:auto;">
    <tr>
      <td align="center" valign="top"  style="background-color:#FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-size: cover;border-top: 0;border-bottom: 0;padding:20px;text-align:left;">
            @if(!empty($maildata['title']))
              <h3 style="font-size:14px;;;padding:0;margin:0;margin-bottom:10px;font-family:Verdana;font-weight:bold;color:#d81f2a;">{{ $maildata['title'] }}</h3>
            @endif
            {!! $maildata['body'] !!}
      </td>
    </tr>
  </table> 
  <!--[if mso]>
  </td>
  </tr>
  </table>
  <![endif]-->
    </td>
  </tr>



@include('mail.footer')