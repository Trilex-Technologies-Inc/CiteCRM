<!-- Main TPL -->
<div class="container-fluid mb-3">
  {include file="core/tool_bar.tpl"}
</div>

<div class="card shadow-sm mb-3">
  <div class="card-header d-flex justify-content-between align-items-center">
    <div>
      {$translate_main_heading}
    </div>
    <div>
      <a href="http://www.citecrm.com/docs/" target="new" class="btn btn-sm btn-outline-light">
        <img src="images/icons/16x16/help.gif" alt="Help" border="0">
      </a>
    </div>
  </div>
  <div class="card-body">
    {if $error_msg != ""}
      {include file="core/error.tpl"}
    {/if}

    <!-- Dashboard summary -->
    <div class="mb-4">
      {include file="stats/summary_cards.tpl"}
    </div>

    <!-- Company notes -->
    <div class="mb-4">
      <h2 class="h6 mb-2">{$translate_main_company_notes}</h2>
      <div class="card card-body p-3">
        { $welcome|default:"Thank you for choosing Cite CRM. Please take the time to register on our web site so we may keep you up to date of changes and bug fixes.<a href='http://www.incitecrm.com/?page=sign_up:main&page_title=Sign%20Up' target='new'>Register Now</a> You can Change this note in the Control Center under company setup."}
      </div>
    </div>

    <!-- Workorder stats -->
    <div class="mb-4">
      <h2 class="h6 mb-2">{$translate_main_workorder_stats}</h2>
      <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>{$translate_main_new}</th>
              <th>{$translate_main_assigned}</th>
              <th>{$translate_main_waiting}</th>
              <th>{$translate_main_payment}</th>
              <th>{$translate_main_closed}</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><a href="?page=workorder:main#new">{$wo_new_count}</a></td>
              <td><a href="?page=workorder:main#assigned">{$wo_ass_count}</a></td>
              <td><a href="?page=workorder:main#awaiting">{$wo_parts_count}</a></td>
              <td><a href="?page=workorder:main#payment">{$wo_pay_count}</a></td>
              <td><a href="?page=workorder:view_closed">{$wo_closed_count}</a></td>
              <td>{$wo_total_count}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Invoice stats -->
    <div class="mb-4">
      <h2 class="h6 mb-2">{$translate_main_invoice_stats}</h2>
      <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>{$translate_main_unpaid}</th>
              <th>{$translate_main_balance}</th>
              <th>{$translate_main_partial_paid}</th>
              <th>{$translate_main_balance}</th>
              <th>{$translate_main_out}</th>
              <th>{$translate_main_paid}</th>
              <th>{$translate_main_total}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <a href="?page=invoice:view_unpaid&page_title=Un-Paid%20Invoices">
                  {$in_unpaid_count}
                </a>
              </td>
              <td class="text-danger">
                ${$in_unpaid_bal|string_format:"%.2f"}
              </td>
              <td>
                <a href="?page=invoice:view_unpaid&page_title=Un-Paid%20Invoices">
                  {$in_part_count}
                </a>
              </td>
              <td class="text-danger">
                ${$in_part_bal|string_format:"%.2f"}
              </td>
              <td class="text-danger">
                ${$in_out_bal|string_format:"%.2f"}
              </td>
              <td>
                <a href="?page=invoice:view_paid&page_title=Paid%20Invoices">
                  {$in_paid_count}
                </a>
              </td>
              <td class="text-success">
                ${$in_total_bal|string_format:"%.2f"}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Customer stats -->
    <div class="mb-2">
      <h2 class="h6 mb-2">{$translate_main_customer_stats}</h2>
      <div class="table-responsive">
        <table class="table table-sm align-middle mb-0">
          <thead class="table-light">
            <tr>
              <th>{$translate_main_new_customers}</th>
              <th>{$translate_main_new_year_customers}</th>
              <th>{$translate_main_total}</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>{$cu_month_count}</td>
              <td>{$cu_year_count}</td>
              <td>{$cu_total_count}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
