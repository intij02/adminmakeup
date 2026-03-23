	<div class="header">
		<div class="container-fluid">
			<div class="row">

				<nav class="navbar navbar-expand-lg" data-bs-theme="dark">
				  <div class="container-fluid">
				    <a class="navbar-brand" href="/">ERP Centro de Mayoreo</a>
				    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
				      <span class="navbar-toggler-icon"></span>
				    </button>
				    <div class="collapse navbar-collapse" id="navbarSupportedContent">
				      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
				      	<?php if(check_permisos('reportes')){ ?>
				        <li class="nav-item">
				          <a class="nav-link active" aria-current="page" href="/reportes">Reportes</a>
				        </li>
				    	<?php } ?>
				    	<?php if(check_permisos('productos')){ ?>
				        <li class="nav-item">
				          <a class="nav-link" href="/productos">Productos</a>
				        </li>
				    	<?php } ?>
				    	<?php if(check_permisos('clientes')){ ?>
				        <li class="nav-item">
				          <a class="nav-link" href="/clientes">Clientes</a>
				        </li>
				    	<?php } ?>
				    	<?php if(check_permisos('pagos')){ ?>
				        <li class="nav-item">
				          <a class="nav-link" href="/pagos">Pagos</a>
				        </li>
				    	<?php } ?>
				    	<?php if(check_permisos('pagos/tienda')){ ?>
				        <li class="nav-item">
				          <a class="nav-link" href="/pagos/tienda">Pagos Tienda</a>
				        </li>
				    	<?php } ?>
				    	<?php if(check_permisos('cotizaciones')){ ?>
				        <li class="nav-item">
				          <a class="nav-link" href="/cotizaciones">Cotizaciones</a>
				        </li>
				    	<?php } ?>
				    	<li class="nav-item">
				    		<a class="nav-link" href="https://msg.centrodemayoreocdmx.com.mx/" target="_blank">Mensajes</a>
				    	</li>
				        <li class="nav-item dropdown">
				          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="true">
				            Admin
				          </a>
				          <ul class="dropdown-menu">
				          	<?php if(check_permisos('admin')){ ?>
				            <li><a class="dropdown-item" href="/admin/usuarios">Usuarios</a></li>
				        	<?php } ?>
				            <?php if(check_permisos('admin')){ ?>
				            <li><a class="dropdown-item" href="/admin/nuevo_usuario">Nuevo Usuario</a></li>
				        	<?php } ?>
				          </ul>
				        </li>
				      </ul>
				      <div class="d-flex text-white" role="search">
				      	<?php echo session()->get('user')['nombre']; ?>
				      </div>
				    </div>
				  </div>
				</nav>


			</div>
		</div>
	</div>
