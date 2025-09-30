 <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark" style="background: #1e293b; color: #fff;">
     <div class="sidebar-brand py-3 px-3 mb-2" style="background: #fff; border-radius: 0.75rem; margin: 1rem;">
         <a href="/backoffice" class="brand-link d-flex align-items-center gap-2" style="color: #2563eb; font-size: 1.4rem; font-weight: 700;">
             <i class="bi bi-bus-front-fill" style="font-size: 2rem; color: #2563eb;"></i>
             <span class="brand-text">Shuttle Bus</span>
         </a>
     </div>

     <div class="sidebar-wrapper">
         <nav class="mt-2">
             <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation"
                 aria-label="Main navigation" data-accordion="false" id="navigation" style="gap: 0.25rem;">



                @if (canMenu('dashboard'))
                <li class="nav-item">
                    <a href="/backoffice" class="nav-link {{ request()->is('backoffice') ? 'active' : '' }} d-flex align-items-center gap-2">
                        <i class="nav-icon ti ti-dashboard" style="font-size: 1.2rem;"></i>
                        <span>แดชบอร์ด</span>
                    </a>
                </li>
                @endif

                @if (canMenu('user_manage'))
                <li class="nav-item">
                    <a href="/backoffice/users"
                        class="nav-link {{ request()->is('backoffice/users*') ? 'active' : '' }} d-flex align-items-center gap-2">
                        <i class="nav-icon ti ti-users" style="font-size: 1.2rem;"></i>
                        <span>จัดการผู้ใช้</span>
                    </a>
                </li>
                @endif

                @if (canMenu('menu_manage'))
                <li class="nav-item">
                    <a href="/backoffice/menus" class="nav-link {{ request()->is('backoffice/menus*') ? 'active' : '' }} d-flex align-items-center gap-2">
                        <i class="nav-icon ti ti-menu-2" style="font-size: 1.2rem;"></i>
                        <span>เมนู</span>
                    </a>
                </li>
                @endif

                @if (canMenu('department_position_manage'))
                <li class="nav-item">
                    <a href="/backoffice/org" class="nav-link {{ (request()->is('backoffice/org') || request()->is('backoffice/departments*') || request()->is('backoffice/positions*')) ? 'active' : '' }} d-flex align-items-center gap-2">
                        <i class="nav-icon ti ti-hierarchy-3" style="font-size: 1.2rem;"></i>
                        <span>จัดการแผนก & ตำแหน่ง</span>
                    </a>
                </li>
                @endif

                @if (canMenu('employee_manage'))
                <li class="nav-item">
                    <a href="/backoffice/employees" class="nav-link {{ request()->is('backoffice/employees*') ? 'active' : '' }} d-flex align-items-center gap-2">
                        <i class="nav-icon ti ti-user-cog" style="font-size: 1.2rem;"></i>
                        <span>พนักงาน</span>
                    </a>
                </li>
                @endif

                @if (canMenu('vehicle_vehicle_type_manage'))
                <li class="nav-item">
                    <a href="/backoffice/vehicles" class="nav-link {{ request()->is('backoffice/vehicles') || request()->is('backoffice/vehicle-types*') || request()->is('backoffice/vehicles*') ? 'active' : '' }} d-flex align-items-center gap-2">
                        <i class="nav-icon ti ti-car" style="font-size: 1.2rem;"></i>
                        <span>รถ & ประเภทรถ</span>
                    </a>
                </li>
                @endif

                @if (canMenu('routes_places_manage'))
                <li class="nav-item">
                    <a href="/backoffice/routes-places" class="nav-link {{ request()->is('backoffice/routes-places') ? 'active' : '' }} d-flex align-items-center gap-2">
                        <i class="nav-icon ti ti-road" style="font-size: 1.2rem;"></i>
                        <span>เส้นทาง & จุดรับ–ส่ง</span>
                    </a>
                </li>
                @endif

                @if (canMenu('trips_manage'))
                <li class="nav-item">
                    <a href="/backoffice/trips" class="nav-link {{ request()->is('backoffice/trips*') ? 'active' : '' }} d-flex align-items-center gap-2">
                        <i class="nav-icon ti ti-bus" style="font-size: 1.2rem;"></i>
                        <span>รอบรถ (Trips)</span>
                    </a>
                </li>
                @endif

                 {{-- <li class="nav-item">
                     <a href="#" class="nav-link">
                         <i class="nav-icon bi bi-ui-checks-grid"></i>
                         <p>
                             Components
                             <i class="nav-arrow bi bi-chevron-right"></i>
                         </p>
                     </a>
                     <ul class="nav nav-treeview">
                         <li class="nav-item">
                             <a href="./docs/components/main-header.html" class="nav-link">
                                 <i class="nav-icon bi bi-circle"></i>
                                 <p>Main Header</p>
                             </a>
                         </li>
                         <li class="nav-item">
                             <a href="./docs/components/main-sidebar.html" class="nav-link">
                                 <i class="nav-icon bi bi-circle"></i>
                                 <p>Main Sidebar</p>
                             </a>
                         </li>
                     </ul>
                 </li> --}}

             </ul>

         </nav>
     </div>

 </aside>
