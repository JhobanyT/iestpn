<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>@yield('title')</title>
		<link rel="icon" href="{{ asset('images/logo/logo.png') }}">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
		<link rel="stylesheet" href="{{ asset('css/layout.css') }}">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.1.4/toastr.min.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

		<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
		<script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
		<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

	</head>
    <body>
		<div id="mySidenav" class="sidenav">
			<p class="logo d-flex justify-content-center"><span><img class="logo-iestpn" src="{{ asset('images/logo/logo.png') }}" /></span></p>
			<p class="nombre-iestpn text-white h6 d-flex text-center">INSTITUTO DE EDUCACION SUPERIOR TECNOLOGICO PUBLICO DE NUÑOA</p>
			<div class="border border-succes border-top-2 mt-4 mb-2"></div>
			<a href="{{ url('trabajoAplicacion') }}" class="icon-a"><i class="fa fa-files-o icons" aria-hidden="true"></i><p class="letra_icon d-inline"> Trabajos de aplicación </p></a>
			<a href="{{ url('programaEstudios') }}"class="icon-a"><i class="fa fa-book icons" aria-hidden="true"></i><p class="letra_icon d-inline"> Programa de estudios </p></a>
			<a href="#"class="icon-a"><i class="fa fa-users icons"></i><p class="letra_icon d-inline"> Usuarios </p></a>
			<a href="#"class="icon-a position-absolute bottom-0 log-out text-center"><i class="fa fa-sign-out icons"></i> Cerrar Sesión</a>
		</div>
		<div id="main">
			<div class="head">
				<div class="col-div-2">
					<span style="font-size:30px;cursor:pointer; color: blabk;" class="nav"><i class="fa fa-bars"></i></span>
					<span style="font-size:30px;cursor:pointer; color: blabk;" class="nav2"><i class="fa fa-bars"></i></span>
				</div>
				<div class="col-div-4 no-v">
					<p >.</p>
				</div>
				<div class="col-div-6 rigth">
					<div class="profile">
						<i class="fa fa-user-circle pro-img fa-3x" aria-hidden="true"></i>
						<p>Jhobany Ticona <span>Administrador</span></p>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		<div class="border-dark border-bottom mb-2">
			<h4>
				@yield('title')
			</h4>
		</div>
		<div> 
			@yield('content')
		</div>
	
	
	<script>

	  $(".nav").click(function(){
	    $("#mySidenav").css('width','70px');
	    $("#main").css('margin-left','70px');
	    $(".logo").css('visibility', 'hidden');
		$(".nombre-iestpn").css('display', 'none').css('cssText', 'display: none !important;');
	    $(".logo span").css('visibility', 'visible');
	    $(".logo span").css('margin-left', '-10px');
		$(".logo span img").css('height', '60px');
	    $(".icon-a").css('visibility', 'hidden');
	    $(".icons").css('visibility', 'visible');
	    $(".icons").css('margin-left', '-8px');
		$(".nav").css('display','none');
	    $(".nav2").css('display','block');
		$(".log-out").css('margin-left','-40px');
		$(".log-out").css('padding-right','0px');
		$(".log-out").css('padding-left','0px');
		$(".letra_icon").css('display', 'none').css('cssText', 'display: none !important;');
	  });

	$(".nav2").click(function(){
		var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
    	if (width > 600) {
	    $("#mySidenav").css('width','300px');
	    $("#main").css('margin-left','300px');
	    $(".logo").css('visibility', 'visible');
		$(".logo span img").css('height', '150px');
		$(".nombre-iestpn").css('display', 'block');
	    $(".icon-a").css('visibility', 'visible');
	    $(".icons").css('visibility', 'visible');
	    $(".nav").css('display','block');
	    $(".nav2").css('display','none');
		$(".log-out").css('margin-left','28px');
		$(".log-out").css('padding-right','50px');
		$(".log-out").css('padding-left','70px');
		$(".letra_icon").css('display', 'inline');
		}
	 });

	function ocultarDivEnPantallaPequena() {
		var width = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		var div = $("#mySidenav");

		if (width <= 600) {
		div.css('width', '70px');
		$("#main").css('margin-left', '70px');
		$(".logo").css('visibility', 'hidden');
		$(".nombre-iestpn").css('display', 'none').css('cssText', 'display: none !important;');
		$(".logo span").css('visibility', 'visible');
		$(".logo span").css('margin-left', '-10px');
		$(".logo span img").css('height', '60px');
		$(".icon-a").css('visibility', 'hidden');
		$(".icons").css('visibility', 'visible');
		$(".icons").css('margin-left', '-8px');
		$(".nav").css('display', 'none');
		$(".nav2").css('display', 'flex');
		$(".log-out").css('margin-left', '-40px');
		$(".log-out").css('padding-right', '0px');
		$(".log-out").css('padding-left', '0px');
		$(".pro-img").css('display', 'none');
		$(".nav2").css('display', 'none');
		$(".letra_icon").css('display', 'none').css('cssText', 'display: none !important;');
		$("#mySidenav").off('click');
		} else {
		div.css('width', '');
		$("#mySidenav").css('width', '300px');
		$("#main").css('margin-left', '300px');
		$(".logo").css('visibility', 'visible');
		$(".logo span img").css('height', '150px');
		$(".nombre-iestpn").css('display', 'block');
		$(".icon-a").css('visibility', 'visible');
		$(".icons").css('visibility', 'visible');
		$(".nav").css('display', 'block');
		$(".nav2").css('display', 'none');
		$(".log-out").css('margin-left', '28px');
		$(".log-out").css('padding-right', '50px');
		$(".log-out").css('padding-left', '70px');
		$(".pro-img").css('display', 'block');
		$(".letra_icon").css('display', 'inline');
		$("#mySidenav").on('click', function(event) {
			event.stopPropagation();
		});
		}
  	}
  	$(window).on('load resize', ocultarDivEnPantallaPequena);
	</script>

	</body>
        
	
</html>