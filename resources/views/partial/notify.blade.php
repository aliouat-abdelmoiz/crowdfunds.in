@if (Session::has('success'))
<script>
    swal("{{ Session::get('success') }}", "Any problem get our support", "success");
</script>
@endif
@if (Session::has('deleted'))
    <script>
        swal("{{ Session::get('deleted') }}", "Any problem get our support", "success");
    </script>
@endif