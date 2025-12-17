<!-- Main TPL --><table  class="toolbar" border="0" cellpadding="0" cellspacing="0" width="100%">
  <tr>
    <td >
      <table  cellpadding="2" cellspacing="2">
        <tr>
           {include file="core/tool_bar.tpl"}
        </tr>
      </table>
    </td>
  </tr>
</table><table width="100%" border="0" cellpadding="20" cellspacing="5">
  <tr>
    <td>
      <table width="700" cellpadding="4" cellspacing="0" border="0" >
        <tr>
          <td class="menuhead2" width="80%">
             &nbsp;{$translate_main_heading}
          </td>
          <td class="menuhead2" width="20%" align="right" valign="middle">
            <a href="http://www.citecrm.com/docs/" target="new"><img src="images/icons/16x16/help.gif" border="0"></a>
          </td>
        </tr>
        <tr>
          <td class="menutd2" colspan="2">
            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
              <tr>
                <td class="menutd">
                   {if $error_msg != ""}
                  <br>
                   {include file="core/error.tpl"}
                  <br>
                   {/if}<!-- Content -->
                  <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                    <tr class="olotd4">
                      <td class="row2">
                        <b>{$translate_main_company_notes}</b>
                    </tr>
                    <tr class="olotd4">
                      <td>
                         { $welcome|default:"Thank you for choosing Cite CRM. Please take the time to register on our web site so we may keep you up to date of changes and bug fixes.<a href='http://www.incitecrm.com/?page=sign_up:main&page_title=Sign%20Up' target='new'>Register Now</a> You can Change this note in the Control Center under company setup."}
                      </td>
                    </tr>
                  </table>
                  <br>
                  <b>{$translate_main_workorder_stats}</b>
                  <br>
                  <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                    <tr class="olotd4">
                      <td class="row2">
                        <b>{$translate_main_new}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_assigned}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_waiting}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_payment}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_closed}</b>
                      </td>
                      <td class="row2">
                        <b>Total</b>
                      </td>
                    </tr>
                    <tr class="olotd4">
                      <td>
                        <a href="?page=workorder:main#new">{$wo_new_count}</a>
                      </td>
                      <td>
                        <a href="?page=workorder:main#assigned">{$wo_ass_count}</a>
                      </td>
                      <td>
                        <a href="?page=workorder:main#awaiting">{$wo_parts_count}</a>
                      </td>
                      <td>
                        <a href="?page=workorder:main#payment">{$wo_pay_count}</a>
                      </td>
                      <td>
                        <a href="?page=workorder:view_closed">{$wo_closed_count}</a>
                      </td>
                      <td>
                         {$wo_total_count}
                      </td>
                    </tr>
                  </table>
                  <br>
                  <b>{$translate_main_invoice_stats}</b>
                  <br>
                  <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                    <tr class="olotd4">
                      <td class="row2">
                        <b>{$translate_main_unpaid}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_balance}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_partial_paid}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_balance}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_out}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_paid}</b>
                      </td>
                      <td class="row2">
                        <b>{$translate_main_total}</b>
                      </td>
                    </tr>
                    <tr class="olotd4">
                      <td>
                        <a href="?page=invoice:view_unpaid&page_title=Un-Paid%20Invoices">{$in_unpaid_count}</a>
                      </td>
                      <td>
                        <font color="#cc0000">${$in_unpaid_bal|string_format:"%.2f"}</font>
                      </td>
                      <td>
                        <a href="?page=invoice:view_unpaid&page_title=Un-Paid%20Invoices">{$in_part_count}</a>
                      </td>
                      <td>
                        <font color="#cc0000">${$in_part_bal|string_format:"%.2f"}</font>
                      </td>
                      <td>
                        <font color="#cc0000">${$in_out_bal|string_format:"%.2f"}</font>
                      </td>
                      <td>
                        <a href="?page=invoice:view_paid&page_title=Paid%20Invoices">{$in_paid_count}</a>
                      </td>
                      <td>
                        <font color="green">${$in_total_bal|string_format:"%.2f"}</font>
                      </td>
                    </tr>
                  </table>
                  <br>
                  <b>{$translate_main_customer_stats}</b>
                  <br>
                  <table width="100%" cellpadding="4" cellspacing="0" border="0" width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                    <tr class="olotd4">
                      <td class="row2">
                        <b>{$translate_main_new_customers}<b></td><td class="row2">
                          <b>{$translate_main_new_year_customers}</b>
                        </td><td class="row2">
                          <b>{$translate_main_total}</b>
                        </td></tr><tr class="olotd4">
                          <td>
                             {$cu_month_count}
                          </td>
                          <td>
                             {$cu_year_count}
                          </td>
                          <td>
                             {$cu_total_count}
                          </td>
                        </tr></table><br></td></tr></table></td></tr></table></td></tr></table>









