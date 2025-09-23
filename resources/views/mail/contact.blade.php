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
      <td align="center" valign="top"  style="background-color:#FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-size: cover;border-top: 0;border-bottom: 0;padding:20px;text-align:center;">
            <h1 style="text-transform: uppercase; font-size: 26px;padding:0;margin:0;font-family:Verdana;font-weight:bold;color:#cc1e24;line-height: 42px;">{{ $maildata['title'] }}</h1>
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="height:10px">
              <tr><td style="height:10px;font-size: 1px;">&nbsp;</td></tr>
            </table>
            <p style="font-size:14px;;line-height: 135%;color:#414042;padding:0;margin:0;font-family:arial;">{!! $maildata['shortdesc'] !!}</p>
                      <table border="0" cellpadding="0" cellspacing="0" width="100%" style="height:5px">
                        <tr><td style="height:5px;font-size: 1px;">&nbsp;</td></tr>
                      </table>
      </td>
    </tr>
  </table> 
  <table align="center" lang=x-outer class="x-outer"  border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse:collapse;mso-table-lspace: 0pt;mso-table-rspace: 0pt;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;margin: 0;padding: 0;width: 100%;background-color: #FFF;max-width:600px;width:100%;margin:auto;">
    <tr>
      <td align="center" valign="top"  style="background-color:#FFFFFF;mso-line-height-rule: exactly;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;background-size: cover;border-top: 0;border-bottom: 0;padding:20px;text-align:left;">
            <h3 style="font-size:14px;;;padding:0;margin:0;margin-bottom:10px;font-family:Verdana;font-weight:bold;color:#d81f2a;">{{ $maildata['subtitle'] }}</h3>
            <p style="font-size:14px;;line-height: 135%;color:#414042;padding:0;margin:0;font-family:arial;">{!! $maildata['body'] !!}<br><br></p>
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