<ul class="nav nav-pills mb-4 nav-fill" role="tablist">
    <li class="nav-item mb-1 mb-sm-0">
        <a href="/direktur/jobdesk-laporan" type="button" class="nav-link <?php echo request()->is('direktur/jobdesk-laporan') ? 'active' : '' ?>" role="tab">
            <span class="d-none d-sm-inline-flex align-items-center">
                <i class="icon-base ri ri-home-smile-line icon-sm me-1_5"></i>Jobdesk
                <span class="badge rounded-pill badge-center h-px-20 w-px-20 bg-danger ms-1_5">3</span>
            </span>
            <i class="icon-base ri ri-home-smile-line icon-sm d-sm-none"></i>
        </a>
    </li>
    <li class="nav-item mb-1 mb-sm-0">
        <a href="/direktur/jobdesk-laporan/jobdesk-karyawan" type="button" class="nav-link <?php echo request()->is('direktur/jobdesk-laporan/jobdesk-karyawan') ? 'active' : '' ?>" role="tab">
            <span class="d-none d-sm-inline-flex align-items-center"><i
                    class="icon-base ri ri-user-3-line icon-sm me-1_5"></i>Jobdesk Karyawan</span>
            <i class="icon-base ri ri-user-3-line icon-sm d-sm-none"></i>
        </a>
    </li>

</ul>
