<div class="container my-4">

    <div class="mb-3">
        {include file="core/admin_tool_bar.tpl"}
    </div>

    {if $error_msg != ""}
        {include file="core/error.tpl"}
    {/if}

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">
            <i class="bi bi-gear-fill me-2 text-secondary"></i> Control Center
        </div>
        <div class="card-body">
            <p class="mb-1">
                Welcome to the Admin Section.
            </p>
            <p class="mb-0 text-muted">
                Select an option below to manage settings, permissions, company info, and more.
            </p>
        </div>
    </div>

    <div class="row g-4">
        
        <div class="col-sm-6 col-lg-4">
            <a href="?page=control:main&page_title={$translate_core_control|default:"Control Center"}" class="text-decoration-none">
                <div class="card h-100 shadow-sm border-light" style="transition: all 0.2s ease-in-out; border-width: 1px; border-style: solid; cursor: pointer; border-radius: 0.75rem;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-sliders fs-2 text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">{$translate_core_control|default:"Control Center"}</h5>
                            <small class="text-secondary-emphasis">Main dashboard & core settings</small>
                        </div>
                       
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-lg-4">
            <a href="?page=control:company_edit&page_title=Company" class="text-decoration-none">
                <div class="card h-100 shadow-sm {if $current_module == 'control' && $current_page == 'company_edit'}border-primary bg-light-subtle{else}border-light{/if}" style="transition: all 0.2s ease-in-out; border-width: 1px; border-style: solid; cursor: pointer; border-radius: 0.75rem;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-building fs-2 text-secondary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Company</h5>
                            <small class="text-secondary-emphasis">Edit company details & profile</small>
                        </div>
                        {if $current_module == 'control' && $current_page == 'company_edit'}
                            <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                        {/if}
                    </div>
                </div>
            </a>
        </div>

        <div class="col-sm-6 col-lg-4">
            <a href="?page=control:acl&page_title=Permissions" class="text-decoration-none">
                <div class="card h-100 shadow-sm {if $current_module == 'control' && $current_page == 'acl'}border-primary bg-light-subtle{else}border-light{/if}" style="transition: all 0.2s ease-in-out; border-width: 1px; border-style: solid; cursor: pointer; border-radius: 0.75rem;">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <i class="bi bi-shield-lock fs-2 text-danger"></i>
                        </div>
                        <div class="flex-grow-1">
                            <h5 class="card-title mb-1">Permissions</h5>
                            <small class="text-secondary-emphasis">User roles & access control</small>
                        </div>
                        {if $current_module == 'control' && $current_page == 'acl'}
                            <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                        {/if}
                    </div>
                </div>
            </a>
        </div>

        {if $show_admin_menu|default:false}
            <div class="col-sm-6 col-lg-4">
                <a href="?page=control:hours_edit&page_title=Office%20Hours" class="text-decoration-none">
                    <div class="card h-100 shadow-sm {if $current_module == 'control' && $current_page == 'hours_edit'}border-primary bg-light-subtle{else}border-light{/if}" style="transition: all 0.2s ease-in-out; border-width: 1px; border-style: solid; cursor: pointer; border-radius: 0.75rem;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-clock fs-2 text-info"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Office Hours</h5>
                                <small class="text-secondary-emphasis">Set working hours & schedule</small>
                            </div>
                            {if $current_module == 'control' && $current_page == 'hours_edit'}
                                <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            {/if}
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="?page=control:payment_options&page_title=Payment%20Methods" class="text-decoration-none">
                    <div class="card h-100 shadow-sm {if $current_module == 'control' && $current_page == 'payment_options'}border-primary bg-light-subtle{else}border-light{/if}" style="transition: all 0.2s ease-in-out; border-width: 1px; border-style: solid; cursor: pointer; border-radius: 0.75rem;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-credit-card fs-2 text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Payment Methods</h5>
                                <small class="text-secondary-emphasis">Manage accepted payments</small>
                            </div>
                            {if $current_module == 'control' && $current_page == 'payment_options'}
                                <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            {/if}
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="?page=control:edit_rate&page_title=Billing%20Rates" class="text-decoration-none">
                    <div class="card h-100 shadow-sm {if $current_module == 'control' && $current_page == 'edit_rate'}border-primary bg-light-subtle{else}border-light{/if}" style="transition: all 0.2s ease-in-out; border-width: 1px; border-style: solid; cursor: pointer; border-radius: 0.75rem;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-cash-coin fs-2 text-warning"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Billing Rates</h5>
                                <small class="text-secondary-emphasis">Hourly rates & fee structures</small>
                            </div>
                            {if $current_module == 'control' && $current_page == 'edit_rate'}
                                <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            {/if}
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="?page=control:check_updates&page_title=Check%20For%20Updates" class="text-decoration-none">
                    <div class="card h-100 shadow-sm {if $current_module == 'control' && $current_page == 'check_updates'}border-primary bg-light-subtle{else}border-light{/if}" style="transition: all 0.2s ease-in-out; border-width: 1px; border-style: solid; cursor: pointer; border-radius: 0.75rem;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-arrow-repeat fs-2 text-secondary"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Check for Updates</h5>
                                <small class="text-secondary-emphasis">System updates & patches</small>
                            </div>
                            {if $current_module == 'control' && $current_page == 'check_updates'}
                                <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            {/if}
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-sm-6 col-lg-4">
                <a href="?page=cats:main&page_title=Category" class="text-decoration-none">
                    <div class="card h-100 shadow-sm {if $current_module == 'cats'}border-primary bg-light-subtle{else}border-light{/if}" style="transition: all 0.2s ease-in-out; border-width: 1px; border-style: solid; cursor: pointer; border-radius: 0.75rem;">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <i class="bi bi-tags fs-2 text-success"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-1">Categories</h5>
                                <small class="text-secondary-emphasis">Manage tags & categories</small>
                            </div>
                            {if $current_module == 'cats'}
                                <i class="bi bi-check-circle-fill text-primary fs-5"></i>
                            {/if}
                        </div>
                    </div>
                </a>
            </div>
        {/if}
        
        {if !($show_admin_menu|default:false)}
            <div class="col-12 text-center text-muted mt-2">
                <hr class="my-3">
                <small><i class="bi bi-info-circle"></i> Additional admin tools may appear based on your permissions.</small>
            </div>
        {/if}
    </div>
</div>

