
<!-- Vendor -->
<script src="{{asset('assets/admin/libs/jquery/jquery.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/node-waves/waves.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/waypoints/lib/jquery.waypoints.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/jquery.counterup/jquery.counterup.min.js')}}"></script>
<script src="{{asset('assets/admin/libs/feather-icons/feather.min.js')}}"></script>

<!-- Apexcharts JS -->
<script src="{{asset('assets/admin/libs/apexcharts/apexcharts.min.js')}}"></script>

<!-- Widgets Init Js -->
<script src="{{asset('assets/admin/js/pages/crm-dashboard.init.js')}}"></script>

<!-- App js-->
<script src="{{asset('assets/admin/js/app.js')}}"></script>
<script>
    document.querySelectorAll('.schedule-row').forEach(row => {

        const checkbox = row.querySelector('.day-off');
        const start = row.querySelector('.start-time');
        const end = row.querySelector('.end-time');

        function toggle() {
            if (checkbox.checked) {
                start.disabled = true;
                end.disabled = true;
                start.value = '';
                end.value = '';
                row.style.opacity = "0.5";
            } else {
                start.disabled = false;
                end.disabled = false;
                row.style.opacity = "1";
            }
        }

        checkbox.addEventListener('change', toggle);
        toggle(); // при загрузке
    });
</script>
