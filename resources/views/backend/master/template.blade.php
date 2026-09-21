<!DOCTYPE html>
<html lang="en">

<head>
	<script type="text/javascript">var ac_max_results = 0;</script>
	<meta charset="utf-8">
	@include('partials.image-fallback')
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="icon" href="{{ asset('/img/logo.png') }}" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Pasay City Police Clearance System">
	<title>PNP Clearance</title>
	<link rel="stylesheet" href="{{asset('css/jquery.dataTables.min.css')}}">
	<script src="{{ asset('js/jquery.min.js') }}"></script>
	{{-- <script src="{{ asset('backend/js/settings.js') }}"></script> --}}
	<link href="{{ asset('docs/css/modern.css') }}" rel="stylesheet">
	<link href="{{ asset('css/global.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{asset('css/buttons.dataTables.min.css')}}">
	<link href="{{ asset('css/theme.css') }}?v={{ filemtime(public_path('css/theme.css')) }}" rel="stylesheet">
	<script type="text/javascript">var ac_max_results = 0;</script>
	@yield('links')
	@yield('style')
</head>

<body>
	<div class="splash active">
		<div class="splash-icon"></div>
	</div>

	<div class="wrapper">

		<div class="main">
			<!-- header -->
            @include('backend.partial.header')

			<main class="content">
				<!-- content -->
                @yield('content')
			</main>
			@include('backend.partial.footer')
		</div>
	</div>

	<svg width="0" height="0" style="position:absolute">
		<defs>
			<symbol viewBox="0 0 512 512" id="ion-ios-pulse-strong">
				<path d="M448 273.001c-21.27 0-39.296 13.999-45.596 32.999h-38.857l-28.361-85.417a15.999 15.999 0 0 0-15.183-10.956c-.112 0-.224 0-.335.004a15.997 15.997 0 0 0-15.049 11.588l-44.484 155.262-52.353-314.108C206.535 54.893 200.333 48 192 48s-13.693 5.776-15.525 13.135L115.496 306H16v31.999h112c7.348 0 13.75-5.003 15.525-12.134l45.368-182.177 51.324 307.94c1.229 7.377 7.397 11.92 14.864 12.344.308.018.614.028.919.028 7.097 0 13.406-3.701 15.381-10.594l49.744-173.617 15.689 47.252A16.001 16.001 0 0 0 352 337.999h51.108C409.973 355.999 427.477 369 448 369c26.511 0 48-22.492 48-49 0-26.509-21.489-46.999-48-46.999z">
				</path>
			</symbol>
		</defs>
	</svg>

	<script src="{{ asset('backend/js/app.js') }}"></script>

</body>
    <script src="{{ asset('/js/print.min.js') }}"></script>
	<script src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('/js/dataTables.bootstrap.min.js') }}"></script>
	<script src="{{ asset('/js/dataTables.fixedHeader.min.js') }}"></script>
	<script src="{{ asset('/js/dataTables.responsive.min.js') }}"></script>
	<script src="{{ asset('/js/responsive.bootstrap.min.js') }}"></script>
	<script src="{{ asset('docs/js/app.js') }}"></script>
	<script src="{{ asset('backend/js/app.js') }}"></script>
	<script src="{{ asset('/js/pnp-table.js') }}?v={{ filemtime(public_path('js/pnp-table.js')) }}"></script>
	<script src="{{ asset('js/moment.js') }}"></script>
	<script src="{{ asset('/ui/jquery-ui.js') }}"></script>
	<script src="{{ asset('/ui/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('/js/pnp-datepicker.js') }}?v={{ filemtime(public_path('js/pnp-datepicker.js')) }}"></script>
	<script src="{{ asset('/js/pnp-select.js') }}?v={{ filemtime(public_path('js/pnp-select.js')) }}"></script>
	<script>
		// Re-measure DataTables columns once fonts/layout are final (they are
		// initialised before the web font loads and keep stale widths otherwise).
		(function () {
			function adjustTables() {
				if (window.jQuery && jQuery.fn.dataTable && jQuery.fn.dataTable.tables) {
					try { jQuery.fn.dataTable.tables({ visible: true, api: true }).columns.adjust(); } catch (e) {}
				}
			}
			window.addEventListener('load', adjustTables);
			if (document.fonts && document.fonts.ready) { document.fonts.ready.then(adjustTables); }
			window.addEventListener('resize', function () { clearTimeout(window.__pnpDtT); window.__pnpDtT = setTimeout(adjustTables, 150); });
		})();
	</script>
	<script>
		window.pnplogo = function()
		{
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/user/update_status_active/' + 1,
                method: 'post',
                success: function(data) {
                    location.reload();
                }
            });
		}

		window.pasaylogo = function()
		{
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/user/update_status_active/' + 0,
                method: 'post',
                success: function(data) {
                    location.reload();
                }
            });
		}
	</script>
	<style>

	</style>
	@yield('scripts')
</html>
