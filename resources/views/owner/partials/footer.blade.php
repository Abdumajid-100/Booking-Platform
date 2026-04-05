<script src="{{ asset('assets/admin/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>
<script src="{{ asset('assets/admin/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('assets/admin/js/app.js') }}"></script>
<script>
    document.querySelectorAll('.schedule-row').forEach((row) => {
        const checkbox = row.querySelector('.day-off');
        const start = row.querySelector('.start-time');
        const end = row.querySelector('.end-time');

        if (!checkbox || !start || !end) {
            return;
        }

        function toggle() {
            row.style.opacity = checkbox.checked ? '0.5' : '1';
            start.disabled = false;
            end.disabled = false;
        }

        function switchToWorkDayIfTimeEntered() {
            if (start.value || end.value) {
                checkbox.checked = false;
                row.style.opacity = '1';
            }
        }

        checkbox.addEventListener('change', toggle);
        start.addEventListener('input', switchToWorkDayIfTimeEntered);
        end.addEventListener('input', switchToWorkDayIfTimeEntered);

        toggle();
    });
</script>
</body>
</html>
