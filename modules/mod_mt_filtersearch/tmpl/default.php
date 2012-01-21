<?php defined('_JEXEC') or die('Restricted access'); ?>
<div class="border-container bg-container padding10">
<form id="filterSearchForm" action="index.php" method="post">
	<div class="search<?php echo $moduleclass_sfx; ?>">
	<div class="row">
	<label style="float:left;font-weight:bold; margin-right:5px;padding:3px 0;"><small>Palabra clave</small></label>
	<input type="text" size="50" id="link_name" name="link_name" class="inputbox text_area" value="" onclick="this.value=''">
	
	
	<?php 
	//Mostramos el select de categorías si no estamos en ninguna categoría del directorio
	if(empty($cat_id)&&$lists['categories'] ) { ?>
		<?php echo $lists['categories'];
	} ?>
		<?php 
		if($options_area!=null) 
		{
			echo '<select class="inputbox text_area" name="cf'.$options_area['cf_id'].'[]">';
			echo '<option value="">-Área-</option>';
			foreach($options_area['fields'] as $option)
			{			
				echo '<option value="'.$option.'">'.$option.'</option>';
			}
			echo '</select>';
		}
		?>

	<?php
	//Campos de búsqueda extra por categoría
	if(isset($cat_id)&&!empty($cat_id))
	{
		?>
			<fieldset>
			<?php
			echo '<input type="hidden" value="'.$cat_id.'" name="cat_id"/>';
			if($options!=null) 
			{
				echo '<select class="inputbox text_area" name="cf'.$options['cf_id'].'">';
				echo '<option value="">-Tipo de Recurso-</option>';
				foreach($options['fields'] as $option)
				{			
					echo '<option value="'.$option.'">'.$option.'</option>';
				}
				echo '</select>';
			}
			?>
			</fieldset>
		</div><!-- Fin row -->
		<?php
		
		switch($cat_id)
		{
			case 85://Agenda
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
			?>
						<fieldset>
							<input type="hidden" value="2" name="cf33" id="cf33b">
							<label><small>Fecha de inicio</small></label>
							<input type="text" value="Desde" maxlength="10" size="10" readonly="" id="desde" name="desde" class="inputbox">
							<input type="hidden" id="dia_desde" value="" name="cf33_5"/>
							<input type="hidden" id="mes_desde" value="" name="cf33_6"/>
							<input type="hidden" id="anyo_desde" value="" name="cf33_7"/>
							<img id="detailscreated_img" onclick="return showCalendar('desde','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
						<fieldset>
							<input type="text" value="Hasta" maxlength="10" size="10" readonly="" id="hasta" name="hasta" class="inputbox">
							<input type="hidden" id="dia_hasta" value="" name="cf33_8"/>
							<input type="hidden" id="mes_hasta" value="" name="cf33_9"/>
							<input type="hidden" id="anyo_hasta" value="" name="cf33_10"/>
							<img id="detailscreated_img" onclick="return showCalendar('hasta','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
					</div>
					<div class="row">
						<fieldset>
							<input type="hidden" value="2" name="cf33" id="cf34b">
							<label><small>Fecha de fin</small></label>
							<input type="text" value="Desde" maxlength="10" size="10" readonly="" id="desde2" name="desde2" class="inputbox">
							<input type="hidden" id="dia_desde" value="" name="cf34_5"/>
							<input type="hidden" id="mes_desde" value="" name="cf34_6"/>
							<input type="hidden" id="anyo_desde" value="" name="cf34_7"/>
							<img id="detailscreated_img" onclick="return showCalendar('desde2','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
						<fieldset>
							<input type="text" value="Hasta" maxlength="10" size="10" readonly="" id="hasta2" name="hasta2" class="inputbox">
							<input type="hidden" id="dia_hasta" value="" name="cf34_8"/>
							<input type="hidden" id="mes_hasta" value="" name="cf34_9"/>
							<input type="hidden" id="anyo_hasta" value="" name="cf34_10"/>
							<img id="detailscreated_img" onclick="return showCalendar('hasta2','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
					</div>
			<?php
			break;
			case 80://Ferias Comerciales
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
				?>
						<fieldset>
							<input type="hidden" value="2" name="cf33" id="cf33b">
							<label><small>Fecha de inicio</small></label>
							<input type="text" value="Desde" maxlength="10" size="10" readonly="" id="desde" name="desde" class="inputbox">
							<input type="hidden" id="dia_desde" value="" name="cf33_5"/>
							<input type="hidden" id="mes_desde" value="" name="cf33_6"/>
							<input type="hidden" id="anyo_desde" value="" name="cf33_7"/>
							<img id="detailscreated_img" onclick="return showCalendar('desde','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
						<fieldset>
							<input type="text" value="Hasta" maxlength="10" size="10" readonly="" id="hasta" name="hasta" class="inputbox">
							<input type="hidden" id="dia_hasta" value="" name="cf33_8"/>
							<input type="hidden" id="mes_hasta" value="" name="cf33_9"/>
							<input type="hidden" id="anyo_hasta" value="" name="cf33_10"/>
							<img id="detailscreated_img" onclick="return showCalendar('hasta','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
					</div>
					<div class="row">
						<fieldset>
							<input type="hidden" value="2" name="cf33" id="cf34b">
							<label><small>Fecha de fin</small></label>
							<input type="text" value="Desde" maxlength="10" size="10" readonly="" id="desde2" name="desde2" class="inputbox">
							<input type="hidden" id="dia_desde" value="" name="cf34_5"/>
							<input type="hidden" id="mes_desde" value="" name="cf34_6"/>
							<input type="hidden" id="anyo_desde" value="" name="cf34_7"/>
							<img id="detailscreated_img" onclick="return showCalendar('desde2','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
						<fieldset>
							<input type="text" value="Hasta" maxlength="10" size="10" readonly="" id="hasta2" name="hasta2" class="inputbox">
							<input type="hidden" id="dia_hasta" value="" name="cf34_8"/>
							<input type="hidden" id="mes_hasta" value="" name="cf34_9"/>
							<input type="hidden" id="anyo_hasta" value="" name="cf34_10"/>
							<img id="detailscreated_img" onclick="return showCalendar('hasta2','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
					</div>
			<?php
			break;
			case 81://Festivales
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
				echo '</div><!-- Fin row -->';
				
			break;
			case 86://Proyectos
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
			?>
					<div class="row">
						<select class="inputbox text_area" name="cf78[]">
							<option value="">-¿Qué busca?-</option>
							<option value="Colaboradores">Colaboradores</option>
							<option value="Socios">Socios</option>
							<option value="Patrocinio">Patrocinio</option>
							<option value="Financiación">Financiación</option>
							<option value="Difusión">Difusión</option>
						</select>
						<fieldset>
							<input type="hidden" value="2" name="cf33" id="cf33b">
							<label><small>Fecha de inicio</small></label>
							<input type="text" value="Desde" maxlength="10" size="10" readonly="" id="desde" name="desde" class="inputbox">
							<input type="hidden" id="dia_desde" value="" name="cf33_5"/>
							<input type="hidden" id="mes_desde" value="" name="cf33_6"/>
							<input type="hidden" id="anyo_desde" value="" name="cf33_7"/>
							<img id="detailscreated_img" onclick="return showCalendar('desde','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
						<fieldset>
							<input type="text" value="Hasta" maxlength="10" size="10" readonly="" id="hasta" name="hasta" class="inputbox">
							<input type="hidden" id="dia_hasta" value="" name="cf33_8"/>
							<input type="hidden" id="mes_hasta" value="" name="cf33_9"/>
							<input type="hidden" id="anyo_hasta" value="" name="cf33_10"/>
							<img id="detailscreated_img" onclick="return showCalendar('hasta','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
					</div>
					<div class="row">
						<fieldset>
							<input type="hidden" value="2" name="cf33" id="cf34b">
							<label><small>Fecha de fin</small></label>
							<input type="text" value="Desde" maxlength="10" size="10" readonly="" id="desde2" name="desde2" class="inputbox">
							<input type="hidden" id="dia_desde" value="" name="cf34_5"/>
							<input type="hidden" id="mes_desde" value="" name="cf34_6"/>
							<input type="hidden" id="anyo_desde" value="" name="cf34_7"/>
							<img id="detailscreated_img" onclick="return showCalendar('desde2','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
						<fieldset>
							<input type="text" value="Hasta" maxlength="10" size="10" readonly="" id="hasta2" name="hasta2" class="inputbox">
							<input type="hidden" id="dia_hasta" value="" name="cf34_8"/>
							<input type="hidden" id="mes_hasta" value="" name="cf34_9"/>
							<input type="hidden" id="anyo_hasta" value="" name="cf34_10"/>
							<img id="detailscreated_img" onclick="return showCalendar('hasta2','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
					</div>
			<?php
			break;
			case 82://Centros de Recursos y Bibliotecas
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
				echo '</div><!-- Fin row -->';
			break;
			case 84://Mediateca
				echo '<select class="inputbox text_area" name="cf64[]">';
					echo '<option value="">-Idioma-</option>';
					foreach($options_idiomas['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
				echo '</select>';
			?>
			<?php
			break;
			case 76://Convocatorias
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
			?>
						<fieldset>
							<input type="hidden" value="2" name="cf33" id="cf33b">
							<label><small>Fecha de inicio</small></label>
							<input type="text" value="Desde" maxlength="10" size="10" readonly="" id="desde" name="desde" class="inputbox">
							<input type="hidden" id="dia_desde" value="" name="cf33_5"/>
							<input type="hidden" id="mes_desde" value="" name="cf33_6"/>
							<input type="hidden" id="anyo_desde" value="" name="cf33_7"/>
							<img id="detailscreated_img" onclick="return showCalendar('desde','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
						<fieldset>
							<input type="text" value="Hasta" maxlength="10" size="10" readonly="" id="hasta" name="hasta" class="inputbox">
							<input type="hidden" id="dia_hasta" value="" name="cf33_8"/>
							<input type="hidden" id="mes_hasta" value="" name="cf33_9"/>
							<input type="hidden" id="anyo_hasta" value="" name="cf33_10"/>
							<img id="detailscreated_img" onclick="return showCalendar('hasta','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
					</div>
					<div class="row">
						<fieldset>
							<input type="hidden" value="2" name="cf33" id="cf34b">
							<label><small>Fecha de fin</small></label>
							<input type="text" value="Desde" maxlength="10" size="10" readonly="" id="desde2" name="desde2" class="inputbox">
							<input type="hidden" id="dia_desde" value="" name="cf34_5"/>
							<input type="hidden" id="mes_desde" value="" name="cf34_6"/>
							<input type="hidden" id="anyo_desde" value="" name="cf34_7"/>
							<img id="detailscreated_img" onclick="return showCalendar('desde2','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
						<fieldset>
							<input type="text" value="Hasta" maxlength="10" size="10" readonly="" id="hasta2" name="hasta2" class="inputbox">
							<input type="hidden" id="dia_hasta" value="" name="cf34_8"/>
							<input type="hidden" id="mes_hasta" value="" name="cf34_9"/>
							<input type="hidden" id="anyo_hasta" value="" name="cf34_10"/>
							<img id="detailscreated_img" onclick="return showCalendar('hasta2','%d-%m-%Y');" alt="calendar" src="templates/system/images/calendar.png" class="calendar">
						</fieldset>
					</div>
			<?php
			break;
			case 83://Empresas
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
				echo '</div><!-- Fin row -->';
			break;
			case 78://Equipamientos Culturales
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
				echo '</div><!-- Fin row -->';
			break;
			case 77://Estudios
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
				echo '</div><!-- Fin row -->';
			break;
			case 79://Instituciones Culturales
				echo '<div class="row">';
				if($options_paises!=null) 
				{
					echo '<select class="inputbox text_area" name="country">';
					echo '<option value="">-País-</option>';
					foreach($options_paises['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
					echo '</select>';
				}
				echo '</div><!-- Fin row -->';
			break;
			case 87://Tipo de anuncios
				echo '<select class="inputbox text_area" name="cf91">';
					echo '<option value="">-Tipo de Anuncio-</option>';
					foreach($options_tipo_anuncios['fields'] as $option)
					{			
						echo '<option value="'.$option.'">'.$option.'</option>';
					}
				echo '</select>';
			break;
			default:
			break;
		}

	}	
	?>
	
	
	
	</div>
	<script type="text/javascript">
		function formatDate(id)
		{
			var fecha_string = document.getElementById(id).value;
			var fecha = new Array();
			fecha = fecha_string.split('-');
			var dia = fecha[0];
			var mes = fecha[1];
			var anyo = fecha[2];
			
			document.getElementById('dia_'+id).value = dia;
			document.getElementById('mes_'+id).value = mes;
			document.getElementById('anyo_'+id).value = anyo;
		}
	</script>
	<div id="searchbtn">
		<?php if ( $search_button ) { ?>
			<input type="submit" value="Buscar" class="button" onclick="javascript:formatDate('desde');formatDate('hasta');"/>
		<?php } ?>
	
		<?php if ( $advsearch ) { ?>
			<a href="<?php echo $advsearch_link; ?>"><?php echo JText::_( 'Advanced search' ) ?></a>
		<?php } ?>
	</div>
	<input type="hidden" name="task" value="advsearch2" />
	<input type="hidden" name="option" value="com_mtree" />
	<input type="hidden" name="searchcondition" value="2" /> <?php // searchcondition -> value=1 (AND), value=2 (OR) ?>
</form>
</div>