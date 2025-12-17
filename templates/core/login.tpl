<!-- Begin Login.tpl--><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/strict.dtd"><html>
  <head>
    <title>Cite CRM Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="css/default.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <center>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <img src="images/cite_crm.jpg" alt="" width="490" height="114">
          </td>
        </tr>
      </table>
      <table width="100%"  border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="3" background="images/index03.gif">
            <img src="images/index03.gif" alt="" width="100%" height="40">
          </td>
        </tr>
        <tr>
          <td align="center">
            <p>
               &nbsp;
            </p>
            <table class="olotable"  border="0" align="center">
              <tr>
                <td class="olohead">
                   &nbsp;Login
                </td>
              </tr>
              <tr>
                <td class="olotd">
                  <table  cellspacing="5" border="0" cellpadding="5" align="center">
                    <tr>
                      <td>
                        <form action="index.php" method="POST">
                          <table width="25%" cellspacing="0" border="0" cellpadding="5" align="center">
                            <tr>
                              <td>
                                 Login
                              </td>
                              <td>
                                <input type="text" name="login" size="25" class="olotd5" alt="login">
                              </td>
                            </tr>
                            <tr>
                              <td>
                                 Password
                              </td>
                              <td>
                                <input type="password" name="password" size="25"  class="olotd5" alt="password">
                              </td>
                            </tr>
                            <tr>
                              <td colspan="2">
                                <input type="submit" name="submit"  class="olotd5" value="Login">
                              </td>
                            </tr>
                             { if $error_msg != "" }
                            <tr>
                              <td colspan="2" class="error">
                                 {$error_msg}
                              </td>
                               { /if}
                            </tr>
                          </table>
                        </form>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <p>
               &nbsp;
            </p>
            <p>
               &nbsp;
            </p>
            <p>
               &nbsp;
            </p>
            <p>
               &nbsp;
            </p>
            <p>
               &nbsp;
            </p>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" >
                  <span class="text3">Copyright 2005 &copy; Cite CRM <a href="http://www.incitecrm.com" target="new"> www.incitecrm.com</a> All rights reserved.</span>
                </td>
              </tr>
              <tr>
                <td>
                   &nbsp;
                </td>
              </tr>
            </table>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>