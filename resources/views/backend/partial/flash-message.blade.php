@if($message = Session::get('success'))
<div id="app-success-toast" class="app-toast app-toast-success" role="status" aria-live="polite" aria-atomic="true">
	<div class="app-toast-body">{{ $message }}</div>
</div>
<style>
	.app-toast {
		position: fixed;
		top: 18px;
		right: 18px;
		z-index: 9999;
		min-width: 280px;
		max-width: 420px;
		padding: 12px 14px;
		border-radius: 8px;
		color: #fff;
		box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
		opacity: 0;
		transform: translateY(-8px);
		transition: opacity .2s ease, transform .2s ease;
	}

	.app-toast-success {
		background: #16a34a;
	}

	.app-toast.show {
		opacity: 1;
		transform: translateY(0);
	}

	.app-toast.hide {
		opacity: 0;
		transform: translateY(-8px);
	}

	.app-toast-body {
		font-weight: 600;
		line-height: 1.4;
	}
</style>
<script>
	(function () {
		var toast = document.getElementById('app-success-toast');
		if (!toast) return;

		setTimeout(function () {
			toast.classList.add('show');
		}, 30);

		setTimeout(function () {
			toast.classList.add('hide');
			setTimeout(function () {
				if (toast && toast.parentNode) {
					toast.parentNode.removeChild(toast);
				}
			}, 220);
		}, 3200);
	})();
</script>
@endif

@if(count($errors) > 0 )
<div class="alert alert-danger col-md-12 col-lg-12">
	<ul>
		@foreach($errors->all() as $error)
			<li> {{ $error }} </li>
		@endforeach
	</ul>
</div>
@endif
